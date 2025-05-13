<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Output\ConsoleOutput;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exceotion\ProcessFailedException;

class RestoreBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:restore';
    protected $description = 'Restore the database from a .sql file';

    
    public function __construct(){
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    // public function handle()
    // {
    //     $file = $this->argument('file');

    //     if(!file_exists($file)){
    //         $this->error('The specified backup file: '.$file. ' was not found on the server.');
    //         return 1; // exit code 1
    //     }

    //     $output = new ConsoleOutput();
    //     $output->writeln('Executing database restore...');

    //     // Displays loading indicator

    //     $indicator = ['|', '/', '-', '\\'];
    //     $i = 0;

    //     // Using Symfony process instead of proc_open() as it safely escapes arguments
    //     // and prevents shell injection attacks 

    //     $proc = Process::fromShellCommandline(sprintf(
    //         'mysql --user=%s --password=%s --host=%s %s < %s',
    //         escapeshellarg(env('DB_USERNAME')),
    //         escapeshellarg(env('DB_PASSWORD')),
    //         escapeshellarg(env('DB_HOST')),
    //         escapeshellarg(env('DB_DATABASE')),
    //         escapeshellarg($file)
    //     ));

    //     $proc->run(function($type, $buffer) use (&$i, $indicator, $output){

    //         if($type === Process::OUT || $type === Process::ERR){
    //             $output->write("\r".$indicator[$i % 4]);
    //             $i++;
    //             usleep(100000);
    //         }

    //     });

    //     if(!$proc->isSuccessful()){
    //         throw new ProcessFailedException($proc);
    //     }

    //     $output->writeln("\rDatabase restored successfully.");


    // }

    public function handle(){

        $output = new ConsoleOutput();
        $backupDirectory = storage_path('app/backups');

        // here, we check to see if backup dir exists, if not, then we create it 
        if(!File::exists($backupDir)){

            File::makeDirectory($backupDir, 0755, true);
            $output->writeln('<info>Backups directory created!</info>');
        }

        // then we generate a list of all available backup files 

        $files = collect(File::files($backupDir))
        ->filter(fn($file) => $file->getExtension() === 'sql')
        ->map(fn($file) => $file->getRealPath())
        ->values()
        ->all();

        // If there are no backups, we create a backup 

        if(empty($files)){
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

            $proc = Process::fromShellCommandline($dumpCommand);
            $proc->run();

            if(!$proc->isSuccesful()){
                throw new ProcessFailedException($proc);
            }

            $output->writeln("<info>No backups found. Created a new backup at: $dumpPath</info>");
            return 0;

            // Prompt user to select from a list of backups 
            $fileChoices = array_map('basename', $files);
            $selected = $this->choice('Select a backup to restore', $fileChoices);
            $selectedPath = $backupDir . '/' . $selected;

            $output->writeln('Restoring database from selected backup...');

            // here, we restore using selected file
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

            $process = Process::fromShellCommandline($restoreCommand);
            $process->run(function ($type, $buffer) use (&$i, $indicator, $output) {
                if ($type === Process::OUT || $type === Process::ERR) {
                    $output->write("\r" . $indicator[$i % 4]);
                    $i++;
                    usleep(100000);
                }
            });

            if(!$process->isSuccessful()){
                throw new ProcessFailedException($process);
            }

            $output->writeln("\r<info>Database restored successfully from $selected</info>");
            return 0;
        }
    }
}
