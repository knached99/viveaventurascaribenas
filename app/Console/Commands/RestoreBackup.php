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
    protected $signature = 'db:restore {file : The path to the .sql file to restore}';
    protected $description = 'Restore the database from a .sql file';

    
    public function __construct(){
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->argument('file');

        if(!file_exists($file)){
            $this->error('The specified backup file: '.$file. ' was not found on the server.');
            return 1; // exit code 1
        }

        $output = new ConsoleOutput();
        $output->writeln('Executing database restore...');

        // Displays loading indicator

        $indicator = ['|', '/', '-', '\\'];
        $i = 0;

        // Using Symfony process instead of proc_open() as it safely escapes arguments
        // and prevents shell injection attacks 

        $proc = Process::fromShellCommandline(sprintf(
            'mysql --user=%s --password=%s --host=%s %s < %s',
            escapeshellarg(env('DB_USERNAME')),
            escapeshellarg(env('DB_PASSWORD')),
            escapeshellarg(env('DB_HOST')),
            escapeshellarg(env('DB_DATABASE')),
            escapeshellarg($file)
        ));

        $proc->run(function($type, $buffer) use (&$i, $indicator, $output){

            if($type === Process::OUT || $type === Process::ERR){
                $output->write("\r".$indicator[$i % 4]);
                $i++;
                usleep(100000);
            }

        });

        if(!$proc->isSuccessful()){
            throw new ProcessFailedException($proc);
        }

        $output->writeln("\rDatabase restored successfully.");


    }
}
