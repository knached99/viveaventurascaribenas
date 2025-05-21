<?php 

namespace App\Livewire\Pages;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Carbon\Carbon;
use Exception;

class Backups extends Component {

    public $backupDir;
    public array $backups = [];
    public string $success = '';
    public string $error = '';

    public function mount(){
        $this->backupDir = storage_path('app/backups');
        $this->backups = $this->loadBackups();
    }
    

    public function createBackup(){
        $this->success = '';
        $this->error = '';
        Log::info('Iniating backup creation via UI...');
        Log::info('Executing method: '.__FUNCTION__. ' in class: '.__CLASS__);

        // checking if backup directory exists 
        if(!File::exists($this->backupDir)){
            Log::warning('Backup directory does not exist, making one now...');
            File::makeDirectory($this->backupDir, 0755, true);
            Log::info('Backup directory successfully created!');
        }

        Log::info('Connecting to mysql database and generating sql dump...');

        try{

            $timestamp = now()->format('Y-m-d_H-i-s');
            $dumpPath = $this->backupDir . "/backup_$timestamp.sql";
            
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host     = env('DB_HOST');
            $database = env('DB_DATABASE');

            $command = [
                'mysqldump',
                '--user=' . $username,
                '--password=' . $password,
                '--host=' . $host,
                $database,
            ];

            $process = new Process($command);
            $process->run(function($type, $buffer) use($dumpPath){
                file_put_contents($dumpPath, $buffer, FILE_APPEND);
            });

            $this->success = 'Backup is successfully created!';
            $this->backups = $this->loadBackups();
        }

        catch(ProcessFailedException $e){
            $this->error = 'Backup creation failed due to an internal error. If this persists, please contact the developer';
            Log::error('Method: '.__FUNCTION__. ' in class: '.__CLASS__.' failed to execute at '.date('F jS, Y, \a\t H:ia', strtotime(now())). ' due to the following error: '.$e->getMessage());
            return;
        }


    }


    public function restoreFromSelectedBackup($fileName) {
        
        $this->success = '';
        $this->error = '';
        
        Log::info('Executing method: '.__FUNCTION__. ' in class: '.__CLASS__. ' at '.now());
        
        $filePath = $this->backupDir.'/'.basename($fileName);

        if(!File::exists($filePath)) {
            $this->error = 'The selected backup file does not exist';
            Log::error('Backup restore could not be completed because the file: '.$filePath. ' was not found');
            return;
        }

        try { 

            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST');
            $database = env('DB_DATABASE');

            Log::info('Attempting to restore from selected backup file: '.$filePath);

            $command = [
                'mysql',
                '--user='.$username,
                '--password='.$password,
                '--host='.$host, 
                $database,
            ];

            // $process = new Process($command);
            // $process->setInput(file_get_contents($filePath));
            // $process->run();
            $process = new Process($command);
            $process->run(function($type, $buffer) use($filePath){
                file_put_contents($filePath, $buffer, FILE_APPEND);
            });


            $this->success = 'Database restored successfully from backup!';
            Log::info('Backup restore was completed successfully from the selected file: '.$filePath);
            $this->backups = $this->loadBackups();

        }

        catch(Exception $e){

            $this->error = 'An error occurred while attempting to restore the backup';
            Log::error('Exception caught in method: '.__FUNCTION__.' in class:  '.__CLASS__. ' at '.now(). ' error: '.$e->getMessage().'');
        }

        catch(ProcessFailedException $e){
            $this->error = 'An error occurred while attempting to restore the backup';
            Log::error('Exception caught in method: '.__FUNCTION__.' in class:  '.__CLASS__. ' at '.now(). ' error: '.$e->getMessage().'');
            return;
        }

    }
    

   public function deleteBackup($fileName){

    $this->success = '';
    $this->error = '';
    
    try {
        Log::info('Executing method: '.__FUNCTION__. ' in class: '.__CLASS__. ' at '. date('F jS, Y, \a\t H:i:a', strtotime(now())));
        
        // Sanitizing incoming file to protect against malicious user input
        $filePath = $this->backupDir . '/'.basename($fileName);

        Log::info('Checking if backup file: '.$filePath. ' exists...');

        if(File::exists($filePath)){
            Log::info("File found: $filePath. Deleting...");
            $deleted = File::delete($filePath);

            if($deleted){
                $this->success = 'The selected backup has been deleted successfully!';
                Log::info('Backup file: '.$filePath. ' deleted successfully!');
                $this->backups = $this->loadBackups();

            }

            else {

                $this->error = 'Failed to delete the backup file: '.$fileName.'';
                Log::error('Failed to delete: '.$filePath);
                return;
            }
        }

        else{

            $this->error = 'You attmpted to delete a non-existent backup file. If this issue persists, contact the developer';
            Log::warning('Attempted to delete a non-existing fle: '.$filePath);
            return;
        }

    }

    catch(Exception $e){
        $this->error = 'An error occurred while deleting the backup';
        Log::error('Exception caught in method: '.__FUNCTION__. ' in class: '.__CLASS__. ' at: '.now().' Error: '.$e->getMessage());
        return;
    }

   }

    // this method retrieves all available backups and lists them 

    private function loadBackups()
    {
        if(!File::exists($this->backupDir)){
            File::makeDirectory($this->backupDir, 0755, true);
        }
    
        return collect(File::files($this->backupDir))
            ->filter(fn($file) => $file->getExtension() === 'sql')
            ->map(fn($file) => [
                'name' => $file->getFilename(),
                'size' => $file->getSize(),
                'modified' => $file->getMTime(),
                'path' => $file->getRealPath(),
            ])
            ->values()
            ->toArray();
    }
    

    public function render()
    {    
        return view('livewire.pages.backups')->layout('layouts.authenticated-theme');
    }
}    
