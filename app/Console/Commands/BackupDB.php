<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--path= : The path where the backup file should be saved}';

    protected $description = 'Creates a database backup SQL file';

    public function __construct()
    {
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

        // Create the backup directory if it doesn't exist
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        // Ensure the directory is writable
        if (!is_writable($path)) {
            $this->error('Directory is not writable. Please check permissions.');
            return 1;
        }

        $this->info('Generating SQL backup...');

        // Prepare the mysqldump command
        $command = [
            'mysqldump',
            '--user=' . env('DB_USERNAME'),
            '--password=' . env('DB_PASSWORD'),
            '--host=' . env('DB_HOST'),
            '--port=' . env('DB_PORT'),
            env('DB_DATABASE'),
            '--result-file=' . $filePath
        ];

        // Use Symfony Process to execute the command
        $process = new Process($command);

        try {
            $process->mustRun();
            $this->info("Backup created successfully: $filePath");
        } catch (ProcessFailedException $exception) {
            $this->error('Failed to create backup: ' . $exception->getMessage());
            return 1;
        }

        return 0;
    }
}
