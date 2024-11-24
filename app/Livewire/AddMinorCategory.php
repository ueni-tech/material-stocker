<?php

namespace App\Livewire;

use App\Models\MinorCategory;
use Livewire\Component;

class AddMinorCategory extends Component
{
    public $minorCategory;
    public $arrayMinorCategory = [];
    public $sujestMinorCategories = [];
    public $isActive = false;

    protected $rules = [
        'minorCategory' => 'required|string|max:255',
    ];

    public function toArrayMinorCategory(string $sujestMinorCategory = null)
    {
        if ($sujestMinorCategory) {
            $this->addArrayMinorCategory($sujestMinorCategory);
            return;
        }

        $this->validate();
        $this->minorCategory = preg_replace('/( |　)/', '', $this->minorCategory);

        $this->addArrayMinorCategory($this->minorCategory);
    }

    private function addArrayMinorCategory($minorCategory)
    {
        if (!in_array($minorCategory, $this->arrayMinorCategory)) {
            $this->arrayMinorCategory[] = $minorCategory;
        }

        $this->minorCategory = '';
        $this->sujestMinorCategories = [];
        $this->isActive = false;
    }

    public function removeMinorCategory($index)
    {
        unset($this->arrayMinorCategory[$index]);
    }

    public function updatedMinorCategory()
    {
        if (empty($this->minorCategory)) {
            $this->sujestMinorCategories = [];
            $this->isActive = false;
            return;
        }

        $this->validate();
        $this->isActive = true;
        $this->sujestMinorCategories = MinorCategory::where('name', 'like', $this->minorCategory . '%')->get();
    }

    public function render()
    {
        return view('livewire.add-minor-category', [
            'minorCategories' => $this->arrayMinorCategory,
            'sujestMinorCategories' => $this->sujestMinorCategories,
            'isActive' => $this->isActive,
        ]);
    }
}
