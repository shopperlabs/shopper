<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Customers;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Profile extends Component
{
    public Model $customer;

    public function render(): View
    {
        return view('shopper::livewire.components.customers.profile');
    }
}
