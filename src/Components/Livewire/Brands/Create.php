<?php

namespace Shopper\Framework\Components\Livewire\Brands;

use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $name;
    public $slug;
    public $website;
    public $description;
    public $is_enabled;
    public $file;

    public function store()
    {
        $this->validate($this->rules());
    }

    public function removeImage()
    {
        $this->file = null;
    }

    public function updated($field)
    {
        $this->validateOnly($field, $this->rules());
    }

    public function rules()
    {
        return [
            'name' => 'required|max:150|unique:'.shopper_table('brands'),
            'slug' => 'required',
            'file' => 'nullable|image|max:1024',
        ];
    }

    public function render()
    {
        return view('shopper::livewire.brands.create');
    }
}
