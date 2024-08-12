<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Customers;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Core\Models\User;

class Profile extends Component
{
    /**
     * @var User
     */
    public $customer;

    public function render(): View
    {
        return view('shopper::livewire.components.customers.profile');
    }
}
