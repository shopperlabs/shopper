<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Settings\Management;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Core\Models\Role;
use Shopper\Core\Repositories\UserRepository;

class Management extends Component
{
    use WithPagination;

    protected $listeners = ['onRoleAdded' => '$refresh'];

    public function removeUser(int $id): void
    {
        (new UserRepository())->getById($id)->delete();

        $this->dispatchBrowserEvent('user-removed');

        Notification::make()
            ->body(__('shopper::notifications.users_roles.admin_deleted'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.settings.management.index', [
            'roles' => Role::query()
                ->with('users')
                ->where('name', '<>', config('shopper.core.users.default_role'))
                ->orderBy('created_at')
                ->get(),
            'users' => (new UserRepository())
                ->makeModel()
                ->whereHas('roles', function (Builder $query): void {
                    $query->whereIn('name', [config('shopper.core.users.admin_role'), 'manager']);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(3),
        ]);
    }
}
