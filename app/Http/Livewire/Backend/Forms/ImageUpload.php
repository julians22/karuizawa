<?php

namespace App\Http\Livewire\Backend\Forms;

use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class ImageUpload extends Component
{
    use WithFilePond;

    public $file;

    public $featured_image = null;

    public function mount($featured_image = null)
    {
        $this->featured_image = $featured_image;
    }

    public function render()
    {
        return view('livewire.backend.forms.image-upload');
    }
}
