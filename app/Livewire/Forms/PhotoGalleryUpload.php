<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use Livewire\WithFileUploads; 
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use App\Models\PhotoGalleryModel;
use App\Models\TripsModel;
use Illuminate\Support\Facades\Log;

class PhotoGalleryUpload extends Component
{
    use WithFileUploads;

    public $photoLabel = '';
    public $photoDescription = '';
    
    #[Validate('required|array|max:3')]
    public ?array $photos = [];
    public string $tripID = '';
    public string $success = '';
    public string $error = '';
    public string $cacheKey = '';
    public ?object $trips = null;  


    protected $rules = [
        'tripID' => 'required|string',
        'photoLabel' => 'required|string',
        'photoDescription' => 'required|string|max:255',
        'photos.*' => 'image|mimes:jpeg,png,jpg', // Validation for each file
    ];

    public function mount() {
        $this->tripID = Str::uuid();
        $this->trips = TripsModel::select('tripID', 'tripLocation')->get();
        
        Log::info('Trips fetched', ['trips' => $this->trips]);
    }
    

    public function uploadPhotosToGallery(){

        try {
            $this->validate();

            Log::info('Validation passed', [
                'tripID' => $this->tripID,
                'photoLabel' => $this->photoLabel,
                'photoDescription' => $this->photoDescription,
                'photos' => $this->photos
            ]);

            $imageURLs = [];
            $folderPath = storage_path('app/public/photo_gallery');

            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
                Log::info('Created photo_gallery directory: ' . $folderPath);
            }

            foreach ($this->photos as $photo) {
                $filePath = 'photo_gallery/' . $photo->hashName() . '.' . $photo->extension();
                $fullPath = storage_path('app/public/' . $filePath);
                $this->resizeImage($photo->getRealPath(), $fullPath, 525, 351);

                $imageURLs[] = asset('storage/' . $filePath);
                Log::info('Photo processed', ['path' => $fullPath, 'url' => $imageURLs]);
            }

            $data = [
                'tripID' => $this->tripID,
                'photoLabel' => $this->photoLabel,
                'photoDescription' => $this->photoDescription,
                'tripPhotos' => json_encode($imageURLs),
            ];

            Log::info('Data prepared for upload', $data);

            $uploadToGallery = PhotoGalleryModel::create($data);

            if (!$uploadToGallery) {
                throw new \Exception('Failed to upload photo(s) to gallery');
            }

            Log::info('Photo uploaded to gallery successfully');
            $this->success = 'Photo uploaded to gallery!';
        } catch (\Exception $e) {
            Log::error('Error in upload method: ' . $e->getMessage());
            $this->error = 'Upload failed: ' . $e->getMessage();
        }
    }

    public function render() {
        return view('livewire.pages.photo-gallery-upload', [
            //'trips' => $this->trips 
        ]);
    }
    
    private function resizeImage($sourcePath, $destinationPath, $newWidth, $newHeight) {
        $imageType = exif_imagetype($sourcePath);

        Log::info('Resizing image', ['sourcePath' => $sourcePath, 'destinationPath' => $destinationPath]);

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($sourcePath);
                break;
            default:
                Log::error('Unsupported image type: ' . $imageType);
                throw new \Exception('The image type is not supported. Please select a JPEG or PNG image.');
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
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
            imagefill($resizedImage, 0, 0, $transparent);
        }

        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $quality = 90; 
                imagejpeg($resizedImage, $destinationPath, $quality);
                break;
            case IMAGETYPE_PNG:
                $compression = 1; // Lowest compression setting
                imagepng($resizedImage, $destinationPath, $compression);
                break;
        }

        Log::info('Image resized successfully', ['newPath' => $destinationPath]);

        imagedestroy($image);
        imagedestroy($resizedImage);
    }
}
