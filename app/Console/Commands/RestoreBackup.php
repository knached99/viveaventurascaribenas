<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RestoreBackup extends Command
{
    protected $signature = 'db:restore';
    protected $description = 'Restore the database from an existing .sql backup or create one if none exist';
    protected $backupDir;

    public function __construct()
    {
        parent::__construct();
        $this->backupDir = storage_path('app/backups');
    }

    public function handle()
    {
        $output = new ConsoleOutput();

        $options = [
            0 => 'Create new backup',
            1 => 'Choose backup to restore from',
            2 => 'Terminate Program'
        ];
        
        $validChoices = array_keys($options);

        while (true) {
            $output->writeln("\n<info>--- Backup Utility Menu ---</info>");
            foreach ($options as $key => $label) {
                $output->writeln("[$key] $label");
            }

            $choice = (int) $this->ask('Choose an option from the menu:');

            if (!in_array($choice, $validChoices, true)) {
                $this->error("Invalid option, please try again.");
                continue;
            }

            switch ($choice) {
                case 0:
                    $this->createBackup($output);
                    break;

                case 1:
                    $this->restoreBackup($output);
                    break;

                case 2:
                    $output->writeln("<info>Program Terminated.</info>");
                    return 0;
            }

            break;
        }

        return 0;
    }

    private function createBackup()
    {
        Log::info("Initiating backup creation...");
    
        if (!File::exists($this->backupDir)) {
            File::makeDirectory($this->backupDir, 0755, true);
            $this->info('Backups directory created!');
            Log::info("Backup directory created at " . $this->backupDir);
        } else {
            Log::info("Backup directory already exists at " . $this->backupDir);
        }
    
        $timestamp = now()->format('Y-m-d_H-i-s');
        $dumpPath = $this->backupDir . "/backup_$timestamp.sql";
    
        $dumpCommand = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            escapeshellarg(env('DB_USERNAME')),
            escapeshellarg(env('DB_PASSWORD')),
            escapeshellarg(env('DB_HOST')),
            escapeshellarg(env('DB_DATABASE')),
            escapeshellarg($dumpPath)
        );
    
        Log::info("Creating new backup at $dumpPath");
        $this->info('Creating backup. Please wait...');
    
        // Simulated progress bar while running the backup process
        $this->withProgressBar(range(1, 10), function () use ($dumpCommand, $dumpPath) {
            $process = Process::fromShellCommandline($dumpCommand);
            $process->run();
            usleep(150000); // simulate time passing
    
            if (!$process->isSuccessful()) {
                Log::error("Failed to create backup: " . $process->getErrorOutput());
                throw new ProcessFailedException($process);
            }
        });
    
        $this->newLine();
        $this->info("✅ Created a new backup at: $dumpPath");
        Log::info("Backup created successfully at: $dumpPath");
        return 0;
    }
    

    private function restoreBackup($output)
    {
        Log::info("Executing backup restore command...");

        if (!File::exists($this->backupDir)) {
            File::makeDirectory($this->backupDir, 0755, true);
            $output->writeln('<info>Backups directory created.</info>');
            Log::info("[RestoreBackup] Created backup directory at {$this->backupDir}");
        }

        $files = collect(File::files($this->backupDir))
        ->filter(fn($file) => $file->getExtension() === 'sql')
        ->map(function ($file) {
            $path = $file->getRealPath();
            $filename = basename($path);
    
            // Extract the datetime from the filename
            if (preg_match('/backup_(\d{4}-\d{2}-\d{2})_(\d{2}-\d{2}-\d{2})\.sql/', $filename, $matches)) {
                $dateTimeString = "{$matches[1]} {$matches[2]}";
                $dateTime = Carbon::createFromFormat('Y-m-d H-i-s', $dateTimeString);
                $readable = $dateTime->format('l, F jS, Y \a\t g a'); // e.g. Wednesday, July 10th, 2025 at 10 pm
            } else {
                $readable = 'Unknown Date';
            }
    
            return [
                'path' => $path,
                'label' => $readable . " ({$filename})"
            ];
        })
        ->values()
        ->all();

        Log::info("Found " . count($files) . " .sql backup file(s)");

        if (count($files) === 0) {
            $output->writeln("<info>No backups found. Please create a new backup first.</info>");
            return 0;
        }

      
        $fileChoices = array_map(fn($f) => $f['label'], $files);

        $selected = null;

        while (!in_array($selected, $fileChoices)) {
            $selected = $this->choice('Select a backup to restore', $fileChoices, null);
        
            if (!in_array($selected, $fileChoices)) {
                $this->error('❌ Invalid selection. Please choose a valid backup file from the list.');
            }
        }
        

        $selectedIndex = array_search($selected, $fileChoices);
        $selectedPath = collect($files)->firstWhere('label', $selected)['path'];

        

        Log::info("User selected backup file: $selectedPath");
        $this->printAsciiBanner($output);

        $this->output->writeln('<info>Restoring database. Please wait...</info>');
        $this->withProgressBar(range(1, 10), function () use ($selectedPath) {
            $restoreCommand = sprintf(
                'mysql --user=%s --password=%s --host=%s %s < %s',
                escapeshellarg(env('DB_USERNAME')),
                escapeshellarg(env('DB_PASSWORD')),
                escapeshellarg(env('DB_HOST')),
                escapeshellarg(env('DB_DATABASE')),
                escapeshellarg($selectedPath)
            );

            $proc = Process::fromShellCommandline($restoreCommand);
            $proc->run();

            usleep(150000); // Simulate progress

            if (!$proc->isSuccessful()) {
                Log::error("Database restore failed: " . $proc->getErrorOutput());
                throw new ProcessFailedException($proc);
            }
        });

        $this->info("\n✅ Database restored successfully from $selected");
        Log::info("Database restored successfully from $selectedPath");

        return 0;
    }

    private function printAsciiBanner($output)
    {
        $ascii = <<<ASCII
  ____  ____   ____            _             
 |  _ \|  _ \ / ___|  ___ _ __(_)_ __   __ _ 
 | | | | |_) | |  _  / __| '__| | '_ \ / _` |
 | |_| |  _ <| |_| || (__| |  | | | | | (_| |
 |____/|_| \_\\____(_)___|_|  |_|_| |_|\__, |
                                      |___/  

ASCII;
        $output->writeln("<fg=cyan>$ascii</fg=cyan>");
    }
}
