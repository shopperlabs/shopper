<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Reviews;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Core\Models\Review;

class Browse extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.reviews.browse', [
            'total' => Review::query()->count(),
        ]);
    }
}
