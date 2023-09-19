<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Settings\Management;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Framework\Models\User\Permission;
use Shopper\Framework\Models\User\Role;

class Permissions extends Component
{
    public Role $role;

    protected $listeners = ['togglePermission', 'permissionAdded'];

    public function permissionAdded(int $id): void
    {
        $this->role = Role::find($id);
    }

    public function togglePermission(int $id): void
    {
        $permission = Permission::query()->find($id);

        if ($this->role->hasPermissionTo($permission->name)) {
            $this->role->revokePermissionTo($permission->name);

            Notification::make()
                ->title(__('Revoke Permission'))
                ->body(__('Permission :permission has been revoked to this role.', ['permission' => $permission->display_name]))
                ->success()
                ->send();
        } else {
            $this->role->givePermissionTo($permission->name);

            Notification::make()
                ->title(__('Allow Permission'))
                ->body(__('Permission :permission has been given to this role.', ['permission' => $permission->display_name]))
                ->success()
                ->send();
        }
    }

    public function removePermission(int $id): void
    {
        Permission::query()->find($id)->delete();

        Notification::make()
            ->title(__('Deleted'))
            ->body(__('The permission has been correctly removed'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.settings.management.permissions', [
            'groupPermissions' => Permission::query()
                ->with('users')
                ->orderBy('created_at')
                ->get()
                ->groupBy('group_name'),
        ]);
    }
}
