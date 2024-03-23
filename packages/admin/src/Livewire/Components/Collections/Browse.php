<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Collections;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Core\Repositories\Store\CollectionRepository;

class Browse extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.collections.browse', [
            'total' => (new CollectionRepository())->count(),
        ]);
    }
}
