<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup 
                            {--path= : The path where the backup file should be saved}
                            {--tables= : Comma-separated list of tables to backup}';

    protected $description = 'Creates a database backup SQL file, optionally for specific tables';

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
        $tables = $this->option('tables');
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

        // Prepare the backup command
        $dbParams = [
            'user' => escapeshellarg(env('DB_USERNAME')),
            'password' => escapeshellarg(env('DB_PASSWORD')),
            'host' => escapeshellarg(env('DB_HOST')),
            'port' => escapeshellarg(env('DB_PORT')),
            'database' => escapeshellarg(env('DB_DATABASE')),
        ];

        $tableList = $tables ? implode(' ', array_map('escapeshellarg', explode(',', $tables))) : '';
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%s %s %s > %s',
            $dbParams['user'],
            $dbParams['password'],
            $dbParams['host'],
            $dbParams['port'],
            $dbParams['database'],
            $tableList,
            escapeshellarg($filePath)
        );

        // Execute the command
        exec($command, $output, $return_var);

        if ($return_var !== 0) {
            $this->error('Failed to create backup.');
            return 1;
        }

        $this->info("Backup created successfully: $filePath");
    }
}
