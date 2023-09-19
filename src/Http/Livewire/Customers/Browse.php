<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Customers;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Shopper\Framework\Repositories\UserRepository;

class Browse extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.customers.browse', [
            'total' => (new UserRepository())
                ->makeModel()
                ->whereHas('roles', function (Builder $query) {
                    $query->where('name', config('shopper.system.users.default_role'));
                })
                ->count(),
        ]);
    }
}
