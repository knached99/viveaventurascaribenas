<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Output\ConsoleOutput;

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

        $command = sprintf(
            'mysql --user=%s --password=%s --host=%s %s < %s',
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_HOST'),
            env('DB_DATABASE'),
            env('DB_SOCKET'),
            $file
        );

        $process = proc_open($command, [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w']
        ], $pipes);

        while(proc_get_status($process)['running']){
            $output->write("\r" . $indicator[$i % 4]);
            $i++;
            usleep(100000); // Sleeps for 100 milliseconds

        }
        proc_close($process);
        $output->writeln("\rDatabase restored successfully.");

    }
}
