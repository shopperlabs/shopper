<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Settings\Management;

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
            ->title(__('Deleted'))
            ->body(__('Admin deleted successfully'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        $users = (new UserRepository())
            ->makeModel()
            ->whereHas('roles', function (Builder $query) {
                $query->where('name', $this->role->name);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('shopper::livewire.settings.management.users-role', compact('users'));
    }
}
