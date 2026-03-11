<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Customers;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Models\Contracts\ShopperUser;
use Shopper\Traits\HandlesAuthorizationExceptions;

class Profile extends Component
{
    use HandlesAuthorizationExceptions;

    public ShopperUser $customer;

    public function render(): View
    {
        return view('shopper::livewire.components.customers.profile');
    }
}
