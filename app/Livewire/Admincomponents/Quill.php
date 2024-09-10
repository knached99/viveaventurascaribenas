<?php 
namespace App\Livewire\Admincomponents;

use Livewire\Component;

class Quill extends Component
{
    public $value;
    public $quillId;

    public function mount($value = ''){
        $this->value = $value;
        $this->quillId = 'quill-' . uniqid();
    }

    public function updatedValue($value){
        // This method will be triggered when the value changes
        // Debugging purpose: dump the value to check
        // dd($value);
    }

    public function render()
    {
        return view('livewire.admincomponents.quill');
    }
}
