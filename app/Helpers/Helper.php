<?php

namespace App\Helpers;  
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class Helper
{

    // Resize image to reduce memory consumption but also retain aspect ratio and resolution  

    public static function resizeImage($sourcePath, $destinationPath, $newWidth, $newHeight)
    {
        $imageType = exif_imagetype($sourcePath);

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($sourcePath);
                break;

            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($sourcePath);
                break;

            default:
                throw new Exception('The image you selected is not supported. Please select a JPEG or PNG image');
        }

        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);

        $aspectRatio = $originalWidth / $originalHeight;

        if ($newWidth / $newHeight > $aspectRatio) {
            $newWidth = $newHeight * $aspectRatio;
        } else {
            $newHeight = $newWidth / $aspectRatio;
        }

        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

        if ($imageType == IMAGETYPE_PNG) {
            // Preserve transparency for PNG images
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
            imagefill($resizedImage, 0, 0, $transparent);
        }

        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $quality = 100; // max quality for JPEG images
                imagejpeg($resizedImage, $destinationPath, $quality);
                break;

            case IMAGETYPE_PNG:
                $compression = 0; // PNG does not support compression
                imagepng($resizedImage, $destinationPath, $compression);
                break;
        }

        // Freeing up memory
        imagedestroy($image);
        imagedestroy($resizedImage);
    }


    public static function ip_in_range(string $ip, string $cidr): bool{

        // Assign variables as if they were an array
        list($subnet, $mask) = explode('/', $cidr);

        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        $mask = ~((i << (32 - $mask)) - 1);

        return ($ipLong & $mask) === ($subnetLong & $mask);
    }


    // general helper function which sends notification
    // to specified recipient using specified notification class 
      
    public static function sendNotification(array $data, string $recipient, string $notificationClass){
        Notification::route('mail', $recipient)->notify(new $notificationClass($data));
    }
  
}
