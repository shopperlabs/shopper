<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Customers;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Shopper\Core\Repositories\UserRepository;

class Browse extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.customers.browse', [
            'total' => (new UserRepository())
                ->makeModel()
                ->whereHas('roles', function (Builder $query) {
                    $query->where('name', config('shopper.core.users.default_role'));
                })
                ->count(),
        ]);
    }
}
