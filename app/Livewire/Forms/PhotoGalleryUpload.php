<?php 

namespace App\Livewire\Forms; 

use Livewire\Form;
use Livewire\WithFileUploads; 
use Livewire\Attributes\Validate;
use App\Models\PhotoGalleryModel;
use App\Models\TripsModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\PhotoGalleryUploadException;
use Exception;



class PhotoGalleryUpload extends Form{

    use WithFileUploads;

    // Initializing variables 
    public string $tripID = '';
    public string $photoLabel = '';
    public string $photoDescription = '';
    public array $trips = [];
    
    #[Validate('required|array|max:3')]
    public ?array $photos = [];

    public string $success = '';
    public string $error = '';


    public function rules(){
        return [
            'photos.*'=>'image|mimes:jpeg,png,jpg',
            'photoLabel'=>'required|string',
            'photoDescription'=>'required|max:255',
            'tripID'=>'required',
        ];

    }


    public function mount(){
        $this->trips = TripsModel::select('tripID', 'tripLocation')->get();
        \Log::info('Trips: '. json_encode($this->trips));
    }
    
    public function uploadPhotosToGallery(){
        $this->validate();

  
            $photosArray = [];
            
            $dirPath = storage_path('app/public/photo_gallery');

            if(!file_Exists($dirPath)){
                mkdir($dirPath, 0755, true);
                
            }

            foreach ($this->photos as $photo) {
                // Resize and store the uploaded file
                $image = $photo->getRealPath();
                $filePath = 'photo_gallery/' . time() . '-' . $photo->hashName() . '.'.$photo->extension();
                $fullPath = storage_path('app/public/' . $filePath);

                // Use GD to resize the image
                $this->resizeImage($photo->getRealPath(), $fullPath,  525, 351);

                $imageURLs[] = asset(Storage::url($filePath));
            }
            $data = [
                'photoID'=>Str::uuid(),
                'tripID'=> $this->tripID,
                'photos'=>json_encode($photosArray),
                'photoLabel'=>$this->photoLabel,
                'photoDescription'=>$this->photoDescription
            ];

            
            $upload = PhotoGalleryModel::create($data);

            if(!$upload){
                throw new PhotoGalleryUploadException(__LINE__, __FUNCTION__, __CLASS__);
            }

            $this->success = 'Photos added to the gallery!';
            $this->resetForm();
        }


        private function resetForm(){
            $this->photoLabel = '';
            $this->photoDescription = '';
            $this->photos = [];
        }


        
        public function render(){
            return view('livewire.pages.photo-gallery-upload', [
                'trips'=>$this->trips 
            ]);
        }


        private function resizeImage($sourcePath, $destinationPath, $newWidth, $newHeight) {
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
        
            // Free up memory
            imagedestroy($image);
            imagedestroy($resizedImage);
        }
        



}


?>