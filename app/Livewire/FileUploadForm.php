<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class FileUploadForm extends Component
{
    use WithFileUploads;

    public $majorCategories;
    public $file;
    public $selectedCategory = '';
    public $isFormValid = false;

    public function mount($majorCategories)
    {
        $this->majorCategories = $majorCategories;
    }

    public function updatedFile()
    {
        $this->validateFormState();
    }

    public function updatedSelectedCategory()
    {
        $this->validateFormState();
    }

    private function validateFormState()
    {
        $this->isFormValid = $this->file && $this->selectedCategory !== '';
    }

    public function resetFile()
    {
        $this->file = null;
        $this->isFormValid = false;
    }

    public function render()
    {
        return view('livewire.file-upload-form');
    }
}
