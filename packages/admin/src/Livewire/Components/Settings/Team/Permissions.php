<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Settings\Team;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Core\Models\Permission;
use Shopper\Core\Models\Role;

class Permissions extends Component
{
    public Role $role;

    public function permissionAdded(int $id): void
    {
        $this->role = Role::find($id);
    }

    public function togglePermission(int $id): void
    {
        /** @var Permission $permission */
        $permission = Permission::query()->find($id);

        if ($this->role->hasPermissionTo($permission->name)) {
            $this->role->revokePermissionTo($permission->name);

            Notification::make()
                ->body(__('shopper::notifications.users_roles.permission_revoke', ['permission' => $permission->display_name]))
                ->success()
                ->send();
        } else {
            $this->role->givePermissionTo($permission->name);

            Notification::make()
                ->body(__('shopper::notifications.users_roles.permission_allow', ['permission' => $permission->display_name]))
                ->success()
                ->send();
        }
    }

    public function removePermission(int $id): void
    {
        Permission::query()->find($id)->delete();

        Notification::make()
            ->body(__('shopper::notifications.actions.remove', ['item' => __('shopper::words.permission')]))
            ->success()
            ->send();
    }

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
