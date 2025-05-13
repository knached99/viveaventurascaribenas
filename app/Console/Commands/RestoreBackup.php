<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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
        $output = new ConsoleOutput();
        $backupDir = storage_path('app/backups');

        // Ensuring the backups directory exists
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
            $output->writeln('<info>Backups directory created.</info>');
        }

        // Here, we generate a list of backup .sql files
        $files = collect(File::files($backupDir))
            ->filter(fn($file) => $file->getExtension() === 'sql')
            ->map(fn($file) => $file->getRealPath())
            ->values()
            ->all();

        //If there are no backups, we create a backup
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

            $process = Process::fromShellCommandline($dumpCommand);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $output->writeln("<info>No backups found. Created a new backup at: $dumpPath</info>");
            return 0;
        }

        // We will prompt the user to select a backup file
        $fileChoices = array_map('basename', $files);
        $selected = $this->choice('Select a backup to restore', $fileChoices);
        $selectedPath = $backupDir . '/' . $selected;

        $output->writeln('Restoring database from selected backup...');

        // finally, we restore the backup using the selected file
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
            throw new ProcessFailedException($proc);
        }

        $output->writeln("\r<info>Database restored successfully from $selected</info>");
        return 0;
    }
}
