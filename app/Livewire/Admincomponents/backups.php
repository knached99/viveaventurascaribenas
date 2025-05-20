<?php 

namespace App\Livewire\Admincomponents;

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
        $this->loadBackups();
    }   

    public function createBackup(){

        Log::info('Iniating backup creation via UI...');
        Log::info('Executing method: '.__FUNCTION__. ' in class: '.__CLASS__);

        // checking if backup directory exists 
        if(!File::exists($this->backupDir)){
            Log::warning('Backup directory does not exist, making one now...');
            File::makeDirectory($this->backupDir, 0755, true);
            Log::info('Backup directory successfully created!');
        }

        Log::info('Connecting to mysql database and generating sql dump...');

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

            if(!$process->isSuccessful()){
                throw new ProcessFailedException($process);
            }

            $this->success = 'Backup is successfully created!';
    }


    public function restoreFromSelectedBackup($fileName) {

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

            $process = new Process($command);
            $process->setInput(file_get_contents($filePath));
            $process->run();

            if(!$process->isSuccessful()){ 
                throw new ProcessFailedException($process);
            }

            $this->success = 'Database restored successfully from backup!';
            Log::info('Backup restore was completed successfully from the selected file: '.$filePath);
        }

        catch(Exception $e){

            $this->error = 'An error occurred while attempting to restore the backup';
            Log::error('Exception caught in method: '.__FUNCTION__.' in class:  '.__CLASS__. ' at '.now(). ' error: '.$e->getMessage().'');
        }

    }
    

   public function deleteBackup($fileName){

    try {
        Log::info('Executing method: '.__FUNCTION__. ' in class: '.__CLASS__. ' at '. date('F jS, Y, \a\t H:i:a', strtotime(now())));
        
        // Sanitizing incoming file to protect against malicious user input
        $filePath = $this->backupDir . '/'.basename($fileName);

        Log::info('Checking if backup file: '.$filePath. ' exists...');

        if(Storage::exists($filePath)){
            Log::info("File found: $filePath. Deleting...");
            $deleted = Storage::delete($filePath);

            if($deleted){
                $this->success = 'The selected backup has been deleted successfully!';
                Log::info('Backup file: '.$filePath. ' deleted successfully!');
            }

            else {

                $this->error = 'Failed to delete the backup file: '.$fileName.'';
                Log::error('Failed to delete: '.$filePath);
            }
        }

        else{

            $this->error = 'You attmpted to delete a non-existent backup file. If this issue persists, contact the developer';
            Log::warning('Attempted to delete a non-existing fle: '.$filePath);
        }

    }

    catch(Exception $e){
        $this->error = 'An error occurred while deleting the backup';
        Log::error('Exception caught in method: '.__FUNCTION__. ' in class: '.__CLASS__. ' at: '.now().' Error: '.$e->getMessage());
    }

   }

    // this method retrieves all available backups and lists them 

   private function loadBackups(){
    
    if(!File::exists($this->backupDir)){
        File::makeDirectory($this->backupDir, 0755, true);
    }

    $this->backups = collect(File::files($this->backupDir))
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
        $backups = $this->loadBackups();
    
        return view('livewire.backups', [
            'backups' => $backups,
        ]);
    }
}    
