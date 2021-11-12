<?php

namespace Shopper\Framework\Http\Livewire\Reviews;

use Livewire\Component;
use Shopper\Framework\Models\Shop\Review;

class Browse extends Component
{
    public function render()
    {
        return view('shopper::livewire.reviews.browse', [
            'total' => Review::query()->count(),
        ]);
    }
}
