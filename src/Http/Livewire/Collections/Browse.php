<?php

namespace Shopper\Framework\Http\Livewire\Collections;

use Livewire\Component;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;

class Browse extends Component
{
    public function render()
    {
        return view('shopper::livewire.collections.browse', [
            'total' => (new CollectionRepository())->count(),
        ]);
    }
}
