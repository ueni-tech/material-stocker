<?php

namespace App\Livewire;

use Livewire\Component;

class AddMinorCategory extends Component
{
    public $minorCategory;
    public $arrayMinorCategory = [];

    protected $rules = [
        'minorCategory' => 'required|string|max:255',
    ];

    public function toArrayMinorCategory()
    {
        $this->validate();
        $this->minorCategory = preg_replace('/( |　)/', '', $this->minorCategory);

        $this->arrayMinorCategory[] = $this->minorCategory;
        $this->minorCategory = '';
    }

    public function removeMinorCategory($index)
    {
        unset($this->arrayMinorCategory[$index]);
    }

    public function render()
    {
        return view('livewire.add-minor-category', [
            'minorCategories' => $this->arrayMinorCategory,
        ]);
    }
}
