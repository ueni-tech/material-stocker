<?php

namespace App\Livewire;

use Livewire\Component;

class FileEditForm extends Component
{
    public $majorCategories;
    public $file;
    public $selectedCategory;
    public $isFormValid = false;

    public function mount($majorCategories, $file)
    {
        $this->majorCategories = $majorCategories;
        $this->file = $file;
        $this->selectedCategory = $file->major_category_id;
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

    public function render()
    {
        return view('livewire.file-edit-form');
    }
}
