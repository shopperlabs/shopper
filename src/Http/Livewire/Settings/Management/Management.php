<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Settings\Management;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Models\User\Role;
use Shopper\Framework\Repositories\UserRepository;

class Management extends Component
{
    use WithPagination;

    protected $listeners = ['onRoleAdded' => '$refresh'];

    public function removeUser(int $id): void
    {
        (new UserRepository())->getById($id)->delete();

        $this->dispatchBrowserEvent('user-removed');

        Notification::make()
            ->title(__('shopper::layout.forms.actions.deleted'))
            ->body(__('shopper::notifications.users_roles.admin_deleted'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.settings.management.index', [
            'roles' => Role::query()
                ->with('users')
                ->where('name', '<>', config('shopper.system.users.default_role'))
                ->orderBy('created_at')
                ->get(),
            'users' => (new UserRepository())
                ->makeModel()
                ->whereHas('roles', function (Builder $query) {
                    $query->whereIn('name', [config('shopper.system.users.default_role'), 'manager']);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(3),
        ]);
    }
}
