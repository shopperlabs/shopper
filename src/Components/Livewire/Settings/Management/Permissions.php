<?php

namespace Shopper\Framework\Components\Livewire\Settings\Management;

use Livewire\Component;
use Shopper\Framework\Models\User\Role;

class Permissions extends Component
{
    public Role $role;

    public function render()
    {
        return view('shopper::livewire.settings.management.permissions');
    }
}
