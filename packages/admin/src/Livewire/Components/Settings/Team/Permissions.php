<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Settings\Team;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Shopper\Models\Permission;
use Shopper\Models\Role;
use Shopper\Traits\HandlesAuthorizationExceptions;

class Permissions extends Component
{
    use HandlesAuthorizationExceptions;

    public Role $role;

    public function mount(): void
    {
        $this->authorize('view_users');
    }

    public function togglePermission(int $id): void
    {
        $this->authorize('view_users');
        /** @var Permission $permission */
        $permission = Permission::query()->find($id);

        if ($this->role->hasPermissionTo($permission->name)) {
            $this->role->revokePermissionTo($permission->name);

            Notification::make()
                ->title(__('shopper::notifications.users_roles.permission_revoke', ['permission' => $permission->display_name]))
                ->success()
                ->send();
        } else {
            $this->role->givePermissionTo($permission->name);

            Notification::make()
                ->title(__('shopper::notifications.users_roles.permission_allow', ['permission' => $permission->display_name]))
                ->success()
                ->send();
        }
    }

    public function removePermission(int $id): void
    {
        $this->authorize('view_users');

        Permission::query()->find($id)->delete();

        Notification::make()
            ->title(__('shopper::notifications.delete', ['item' => __('shopper::pages/settings/staff.permission')]))
            ->success()
            ->send();
    }

    #[On('permissionAdded')]
    public function render(): View
    {
        return view('shopper::livewire.components.settings.team.permissions', [
            'groupPermissions' => Permission::query()
                ->with('users')
                ->orderBy('created_at')
                ->get()
                ->groupBy('group_name'),
        ]);
    }
}
