<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Settings\Management;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Shopper\Core\Models\Role;
use Shopper\Core\Repositories\UserRepository;

class UsersRole extends Component
{
    public Role $role;

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
        return view('shopper::livewire.settings.management.users-role', [
            'users' => (new UserRepository())
                ->makeModel()
                ->whereHas('roles', function (Builder $query): void {
                    $query->where('name', $this->role->name);
                })
                ->orderBy('created_at', 'desc')
                ->get(),
        ]);
    }
}
