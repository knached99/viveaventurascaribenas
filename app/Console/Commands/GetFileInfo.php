<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class GetFileInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:info {filePath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves EXIF data and details of a given file';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $filePath = $this->argument('filePath');

        if(!File::exists($filePath)){
            $this->error("File not found: {$filePath}");
            return;
        }

        $fileInfo =  [
            'File Type'=> File::mimeType($filePath),
            'File Size'=> $this->formatFileSize(File::size($filePath)),
            'Uploaded On' => date('F jS, Y \a\t g:i a', strtotime('@' . File::lastModified($filePath))),
            'Stored In'=>Storage::disk('local')->path($filePath),
        ];

        $this->info("File info for: {$filePath}");

        foreach($fileInfo as $key => $value){
            $this->line("{$key}: {$value}");
        }

        // Here, we'll extract the exif data if the file is an image 
        
        if (in_array(File::mimeType($filePath), ['image/jpeg', 'image/png'])) {
            $this->line("\nEXIF Data:");
            
            $absolutePath = Storage::disk('public')->exists($filePath)
            ? Storage::disk('public')->path($filePath)
            : (Storage::disk('local')->exists($filePath)
                ? Storage::disk('local')->path($filePath)
                : null);
        
        if (!$absolutePath) {
            $this->error("File not found in storage.");
            return;
        }
        
        $exif = @exif_read_data($absolutePath, false);
        
            if ($exif) {
                $this->line("FileName: " . basename($filePath));
                $make = $exif['Make'] ?? 'N/A';
                $model = $exif['Model'] ?? 'N/A';
                $this->line("Make: {$make}");
                $this->line("Model: {$model}");
        
                $exposureTime = $exif['ExposureTime'] ?? 'N/A';
                $fNumber = isset($exif['FNumber']) 
                    ? 'f/' . (number_format($exif['FNumber'], 1))
                    : 'N/A';
                $iso = $exif['ISOSpeedRatings'] ?? 'N/A';
                $flash = (isset($exif['Flash']) && $exif['Flash'] == 0) ? 'Off' : 'On';
                $focalLength = isset($exif['FocalLength'])
                    ? (is_array($exif['FocalLength'])
                        ? round($exif['FocalLength'][0] / $exif['FocalLength'][1], 1)
                        : $exif['FocalLength']) . 'mm'
                    : 'N/A';
        
                $this->line("ExposureTime: {$exposureTime}");
                $this->line("FNumber: {$fNumber}");
                $this->line("ISO: {$iso}");
                $this->line("Flash: {$flash}");
                $this->line("FocalLength: {$focalLength}");
        
                $dateTime = $exif['DateTimeOriginal'] ?? 'N/A';
                $this->line("DateTime: {$dateTime}");
        
                if (isset($exif['GPSLatitude']) && isset($exif['GPSLongitude'])) {
                    $lat = $this->getGPSCoordinate(
                        $exif['GPSLatitude'],
                        $exif['GPSLatitudeRef']
                    );
                    $lon = $this->getGPSCoordinate(
                        $exif['GPSLongitude'],
                        $exif['GPSLongitudeRef']
                    );
        
                    $this->line("GPSLatitude: " . ($lat ?? 'N/A'));
                    $this->line("GPSLongitude: " . ($lon ?? 'N/A'));
                } else {
                    $this->line("GPSLatitude: N/A");
                    $this->line("GPSLongitude: N/A");
                }
        
                $whiteBalance = $exif['WhiteBalance'] ?? 'N/A';
                $whiteBalanceText = $whiteBalance == 1 ? 'Auto' : 'Manual';
                $this->line("WhiteBalance: {$whiteBalanceText}");
        
                $comment = $exif['COMMENT'] ?? 'N/A';
                if (is_array($comment)) {
                    $comment = implode(' ', $comment);
                }
                $this->line("COMMENT: {$comment}");
            } else {
                $this->error("No EXIF data found or EXIF is not supported for this file.");
            }
        } else {
            $this->error("File type is not supported for EXIF data.");
        }
        
    }

    /**
 * This function converts GPS coordinates from EXIF format to decimal degrees.
 *
 * @param array $coord
 * @param string $ref
 * @return string|null
 */

    private function getGPSCoordinate(array $coord, string $ref): ?string {
        
        if(empty($coord) || count($coord) < 3){
           
            return null;
        }

        $degrees = $this->gpsToDecimal($coord[0]);
        $minutes = $this->gpsToDecimal($coord[1]);
        $seconds = $This->gpsToDecimal($coord[2]);

        $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);
        $decimal = ($ref === '5' || $ref === 'W') ? -$decimal : $decimal;

        $formattedCoordinate = number_format($decimal, 6);
        $googleMapsLink = "https://www.google.com/maps?q={$formattedCoordinate}";

        return "{$formattedCoordinate} {$ref} (Google Maps: {$googleMapsLink})";
    }

    /**
     * This method converts the EXIF GPS value to a decimal.
     *
     * @param mixed $value
     * @return float
     */

     private function gpsToDecimal($value): float {

        if(is_array($value) && count($value) === 2){

            return $value[1] != 0 ? $value[0] / $value[1] : 0.0;
        }

        return (float) $value;
     }

  /**
     * This function formats the file size into a human-readable format.
     *
     * @param int $bytes
     * @return string
     */

     private function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1073741824) { // 1 GB
            $size = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) { // 1 MB
            $size = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) { // 1 KB
            $size = number_format($bytes / 1024, 2) . ' KB';
        } else {
            $size = $bytes . ' bytes';
        }

        return $size; 
}

}
