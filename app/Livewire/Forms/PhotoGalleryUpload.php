<?php 

namespace App\Livewire\Forms; 

use Livewire\Form;
use Livewire\WithFileUploads; 
use Livewire\Attributes\Validate;
use App\Models\PhotoGalleryModel;
use App\Models\TripsModel;
use app\Helpers\Helper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\PhotoGalleryUploadException;
use Exception;



class PhotoGalleryUpload extends Form {

    use WithFileUploads;

    // Initializing variables 
    public string $tripID = '';
    public string $photoLabel = '';
    public string $photoDescription = '';
    public array $trips = [];
    
    #[Validate('required|array|max:5')]
    public ?array $photos = [];

    public string $success = '';
    public string $error = '';

    protected $rules = [
        'photos.*'=>'image|mimes:jpeg,png,jpg',
        'photoLabel'=>'required|string',
        'photoDescription'=>'required|max:255',
        'tripID'=>'required',
    ];

    protected $messages = [
        'photos.*.image'=>'The file selected is not a valid image',
        'photos.*.mimes'=>'The file selected must be a valid JPEG, JPG, or PNG image',
        'photoLabel.required'=>'You must provide a photo label',
        'photoDescription.required'=>'You must provide a photo description',
        'tripID.required'=>'You must select the trip to associate the photo(s) to',
    ];

    
    public function uploadPhotosToGallery(){
        
        $this->validate();

            $photosArray = [];
            
            $dirPath = storage_path('app/public/photo_gallery');

            if(!file_Exists($dirPath)){
                mkdir($dirPath, 0755, true);
                
            }

      

            foreach ($this->photos as $photo) {
                // Resize and store the uploaded file
                $fileName = $photo->hashName() . '.' . $photo->extension();
                $filePath = 'photo_gallery/' . $fileName;
            
                // Use GD to resize the image (resize using the public disk path)
                // $resizedImagePath = Storage::disk('public')->path($filePath);
                // $this->resizeImage($photo->getRealPath(), $resizedImagePath, 525, 351);
            
                // Store the resized image on the public disk
                $photo->storeAs('photo_gallery', $fileName, 'public');
            
                // Generate the URL to the file using the public disk
               // $photosArray[] = asset(Storage::disk('public')->url($filePath));
               $photosArray[] = $fileName; 
            }
            
            $data = [
                'photoID' => Str::uuid(),
                'tripID' => $this->tripID,
                'photos' => json_encode($photosArray),
                'photoLabel' => $this->photoLabel,
                'photoDescription' => $this->photoDescription
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
            ]);
        }


}


?>