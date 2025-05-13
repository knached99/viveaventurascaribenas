<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class RestoreBackup extends Command
{
    protected $signature = 'db:restore';
    protected $description = 'Restore the database from an existing .sql backup or create one if none exist';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('[RestoreBackup] Command started.');
        $output = new ConsoleOutput();
        $backupDir = storage_path('app/backups');

        // Ensuring the backups directory exists
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
            $output->writeln('<info>Backups directory created.</info>');
            Log::info("[RestoreBackup] Created backup directory at $backupDir.");
        } else {
            Log::info("[RestoreBackup] Backup directory already exists at $backupDir.");
        }

        // Generate a list of backup .sql files
        $files = collect(File::files($backupDir))
            ->filter(fn($file) => $file->getExtension() === 'sql') // filters all file extensions to only look for .sql
            ->map(fn($file) => $file->getRealPath()) // transforms each file object to absolute path
            ->values() // resets array keys to be zero based sequential 
            ->all(); // converts back to PHP array

        Log::info("[RestoreBackup] Found " . count($files) . " .sql backup file(s).");

        // If there are no backups, we create a new backup
        if (empty($files)) {
            $timestamp = now()->format('Y-m-d_H-i-s');
            $dumpPath = $backupDir . "/backup_$timestamp.sql";

            $dumpCommand = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                escapeshellarg(env('DB_USERNAME')),
                escapeshellarg(env('DB_PASSWORD')),
                escapeshellarg(env('DB_HOST')),
                escapeshellarg(env('DB_DATABASE')),
                escapeshellarg($dumpPath)
            );

            Log::info("[RestoreBackup] No backups found. Creating new backup at $dumpPath");
            $prompt = $this->ask("No backups found, would you like to create a new one? Y or y to continue");
            
            if($prompt === 'Y' || $prompt === 'y'){
            

            $process = Process::fromShellCommandline($dumpCommand);
            $process->run();

            if (!$process->isSuccessful()) {
                Log::error('[RestoreBackup] Failed to create backup: ' . $process->getErrorOutput());
                throw new ProcessFailedException($process);
            }

            // $output->writeln("<info>No backups found. Created a new backup at: $dumpPath</info>");
            Log::info("[RestoreBackup] Backup created successfully at $dumpPath");
            return 0;
        }

        else{
            $output->writln("<info>Backup Process Terminated</info>");
            Log::info("User terminated the backup process");
            return 0;
        }
        }

        // Prompt the user to select a backup file
        $fileChoices = array_map('basename', $files);
        $selected = $this->choice('Select a backup to restore', $fileChoices);
        $selectedPath = $backupDir . '/' . $selected;

        Log::info("[RestoreBackup] User selected backup file: $selectedPath");

        $output->writeln('Restoring database from selected backup...');
        Log::info('[RestoreBackup] Beginning restore process...');

        // Restore the backup using the selected file
        $indicator = ['|', '/', '-', '\\'];
        $i = 0;

        $restoreCommand = sprintf(
            'mysql --user=%s --password=%s --host=%s %s < %s',
            escapeshellarg(env('DB_USERNAME')),
            escapeshellarg(env('DB_PASSWORD')),
            escapeshellarg(env('DB_HOST')),
            escapeshellarg(env('DB_DATABASE')),
            escapeshellarg($selectedPath)
        );

        $proc = Process::fromShellCommandline($restoreCommand);
        $proc->run(function ($type, $buffer) use (&$i, $indicator, $output) {
            if ($type === Process::OUT || $type === Process::ERR) {
                $output->write("\r" . $indicator[$i % 4]);
                $i++;
                usleep(100000);
            }
        });

        if (!$proc->isSuccessful()) {
            Log::error('[RestoreBackup] Database restore failed: ' . $proc->getErrorOutput());
            throw new ProcessFailedException($proc);
        }

        $output->writeln("\r<info>Database restored successfully from $selected</info>");
        Log::info("[RestoreBackup] Database restored successfully from $selectedPath");
        return 0;
    }
}
