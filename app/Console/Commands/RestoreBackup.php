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
        $this->backupDir = storage_path('app/backups');
        parent::__construct();
    }

    public function handle()
    {
        $output = new ConsoleOutput();

        $options = [
            0 => 'Create new backup',
            1 => 'Choose backup to restore from',
            2 => 'Terminate Program'
        ];
        
        $validChoices = array_keys($options); // Only the keys (0-2) in the array are valid options
        while(true){
            $output->writeln("\n<info>--- Backup Utility Menu ---</info>");
            foreach($options as $key => $label){
                $output->writeln("[$key] $label");
            }

            $choice = (int) $this->ask('Choose an option from the menu: ');

            if(!in_array($choice, $validChoices, true)){
                $this->error("Invalid option, please try again.");
                continue;
            }

            switch($choice){
                case 0: 
                    $this->createBackup();
                    break;

                case 1:
                    $this->restoreBackup();
                    break;
                
                case 2:
                    $output->writeln("<info>Program Terminated.</info>");
                    return 0;
            }

            break;
        }
       
    }

    private function createBackup(){
        Log::info("Initiating backup creation...");
        
        if(!File::exists($this->backupDir)){
            File::makeDirectory($this->backupDir, 0755, true);
            $output->writeln('<info>Backups directory created!</info>');
            Log::info("Backup directory created at ".$this->backupDir."");
        }

        else{
            Log::info("Backup directory already exists at ".$this->backupDir."");
        }

        $timestamp = now()->format('Y-m-d_H-i-s');
        $dumpPath = $this->backupDir. "/backup_$timestamp.sql";

        $dumpCommand = sprintf('mysqldump --user=%s --password=%s --host=%s %s > %s',
        escapeshellarg(env('DB_USERNAME')),
        escapeshellarg(env('DB_PASSWORD')),
        escapeshellarg(env('DB_HOST')),
        escapeshellarg(env('DB_DATABASE')),
        escapeshellarg($dumpPath));

        Log::info("Creating new backup at ".$dumpPath);

        $process = Process::fromShellCommandline($dumpCommand);
        $process->run();

        if(!$process->isSuccessful()){
            Log::error("Failed to create backup: ".$process->getErrorOutput());
            throw new ProcessFailedException($process);
        }

        $output->writeln("<info>Created a new backup at: $dumpPath</info>");
        Log::info("Backup created successfully at: $dumpPath");
        return 0;
    }


    private function restoreBackup(){
        
        Log::info("<info> Executing backup restore command...");

        if(!File::exists($this->backupDir)){
            File::makeDirectory($backupDir, 0755, true);
            $output->writeln('<info>Backups directory created.</info>');
            Log::info("[RestoreBackup] Created backup directory at $backupDir.");
        }
        else{
            Log::info("Backup directory already exists at $backupDir");
        }

        $files = collect(File::files($backupDir))
        ->filter(fn($file) => $file->getExtension() === 'sql')
        ->map(fn($file) => $file->getRealPath())
        ->values()
        ->all();

        Log::info("Found ".count($files). " .sql backup file(s)");

        if(count($files) === 0){
            $output->writeln("<info> No backups found, exiting program. Re-run the program and select the first option to create a new backup");
            return 0;
        }

        $fileChoices = array_map('basename', $files);
        $selected = $this->choice('Select a backup to restore', $fileChoices);
        $selectedPath = $this->backupDir. '/'. $selected;
        
        Log::info("User selected the following backup file: ".$selectedPath);

        $output->writeln("Restoring database from selected backup...");
        Log::info("Beginning restore process...");

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

        if(!$proc->isSuccessful()){
            Log::error("Database restore failed: ".$proc->getErrorOutput());
            throw new ProcessFailedException($proc);
        }

        $output->writeln("\r<info>Database restored succsesfully from $selected</info>");
        
        Log::info("Database restored successfully from $selectedPath");
        return 0;

    }
}
