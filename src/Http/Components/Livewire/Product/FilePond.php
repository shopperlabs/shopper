<?php

namespace Shopper\Framework\Http\Components\Livewire\Product;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class FilePond extends Component
{
    use WithFileUploads;

    public $productId = 0;
    public $images = [];

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function upload()
    {

    }

    public function render()
    {
        return view('shopper::components.livewire.products.file-pond');
    }
}
