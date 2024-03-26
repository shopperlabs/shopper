<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings\Team;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Core\Models\Role;
use Shopper\Core\Repositories\UserRepository;

#[Layout('shopper::components.layouts.setting')]
class Index extends Component
{
    use WithPagination;

    public function removeUser(int $id): void
    {
        (new UserRepository())->getById($id)->delete();

        Notification::make()
            ->body(__('shopper::notifications.users_roles.admin_deleted'))
            ->success()
            ->send();
    }

    #[On('teamUpdate')]
    public function render(): View
    {
        return view('shopper::livewire.pages.settings.team.index', [
            'roles' => Role::query()
                ->with('users')
                ->where('name', '<>', config('shopper.core.users.default_role'))
                ->orderBy('created_at')
                ->get(),
            'users' => (new UserRepository())
                ->with('roles')
                ->makeModel()
                ->whereHas('roles', function (Builder $query): void {
                    $query->whereIn('name', [config('shopper.core.users.admin_role'), 'manager']);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(3),
        ])
            ->title(__('shopper::pages/settings.roles_permissions.title'));
    }
}
