<?php

namespace App\Livewire;

use Livewire\Component;

class FileUploadForm extends Component
{
    public $majorCategories;

    public function mount($majorCategories)
    {
        $this->majorCategories = $majorCategories;
    }

    public function render()
    {
        return view('livewire.file-upload-form');
    }
}
