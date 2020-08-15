<?php

namespace Shopper\Framework\Http\Components\Livewire;

use Livewire\Component;
use Shopper\Framework\Models\Shop\ShopSize;

class Initialization extends Component
{
    public function render()
    {
        $sizes = ShopSize::all();

        return view('shopper::components.livewire.initialization', compact('sizes'));
    }
}