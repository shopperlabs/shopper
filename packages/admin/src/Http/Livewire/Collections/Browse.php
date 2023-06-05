<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Collections;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Core\Repositories\Ecommerce\CollectionRepository;

class Browse extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.collections.browse', [
            'total' => (new CollectionRepository())->count(),
        ]);
    }
}
