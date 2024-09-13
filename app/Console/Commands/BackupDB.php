<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Output\ConsoleOutput;


class BackupDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--path= : The path where the backup file should be saved}';
    protected $description = 'Creates a database backup sql file';

    
    public function __construct(){
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->option('path') ?: storage_path('app/backups');
        $fileName = 'backup_' . date('Y_m_d_H_i_s') . '.sql';
        $filePath = $path . '/' . $fileName;
    
        // Create directory if it doesn't exist
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    
        // Ensure the directory is writable
        if (!is_writable($path)) {
            $this->error('Directory is not writable. Please check permissions.');
            return 1;
        }
    
        $this->info('Generating SQL backup...');
    
        // Display loading indicator
        $indicator = ['|', '/', '-', '\\'];
        $i = 0;
    
        // Run the backup command
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%s %s > %s',
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_HOST'),
            env('DB_PORT'),
            env('DB_DATABASE'),
            $filePath
        );
    
        // Use exec to execute the command
        exec($command, $output, $return_var);
    
        if ($return_var !== 0) {
            $this->error('Failed to create backup.');
            return 1;
        }
    
        $this->info("Backup created successfully: $filePath");
    }
    
}
