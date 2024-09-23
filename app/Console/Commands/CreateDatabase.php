<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Exception; 

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the databaes if it does not exist.';

    /**
     * Execute the console command.
     * @return int 
     */
    public function handle()
    {
        // Retrieve the database configuration from .env
        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbName = env('DB_DATABASE', 'travelDB');
        $dbUser = env('DB_USERNAME', 'root');
        $dbPass = env('DB_PASSWORD', '');
    
        // Ensure the host is not empty
        if (empty($dbHost)) {
            $this->error('Database host is empty. Please check your .env file.');
            return 1;
        }
    
        // Set the database to null temporarily
        Config::set('database.connections.mysql.database', null);
    
        // Create a connection without specifying the database
        $connection = mysqli_connect($dbHost, $dbUser, $dbPass);
    
        if (!$connection) {
            $this->error('Connection failed: ' . mysqli_connect_error());
            return 1;
        }
    
        try {
            // Create the database
            $query = "CREATE DATABASE IF NOT EXISTS `$dbName`";
            if (mysqli_query($connection, $query)) {
                $this->info("Database `$dbName` created successfully or already exists.");
            } else {
                $this->error('Error creating database: ' . mysqli_error($connection));
            }
    
        } catch (Exception $e) {
            $this->error('Error creating database: ' . $e->getMessage());
        } finally {
            // Close the connection
            mysqli_close($connection);
        }
    
        // Restore the original database configuration
        Config::set('database.connections.mysql.database', $dbName);
    
        return 0;
    }
    
}
