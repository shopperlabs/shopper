<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Settings\Management;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Framework\Models\User\Permission;
use Shopper\Framework\Models\User\Role;
use WireUi\Traits\Actions;

class Permissions extends Component
{
    use Actions;

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

            $this->notification()->success(
                __('Revoke Permission'),
                __('Permission :permission has been revoked to this role.', ['permission' => $permission->display_name])
            );
        } else {
            $this->role->givePermissionTo($permission->name);

            $this->notification()->success(
                __('Allow Permission'),
                __('Permission :permission has been given to this role.', ['permission' => $permission->display_name])
            );
        }
    }

    public function removePermission(int $id): void
    {
        Permission::query()->find($id)->delete();

        $this->notification()->success(__('Deleted'), __('The permission has been correctly removed.'));
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
