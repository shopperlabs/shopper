<?php

namespace Shopper\Framework\Http\Livewire\Settings\Management;

use Livewire\Component;
use Shopper\Framework\Models\User\Permission;
use Shopper\Framework\Models\User\Role;

class Permissions extends Component
{
    public Role $role;

    protected $listeners = ['togglePermission'];

    public function togglePermission(int $id)
    {
        $permission = Permission::query()->find($id);

        if ($this->role->hasPermissionTo($permission->name)) {
            $this->role->revokePermissionTo($permission->name);
            $this->dispatchBrowserEvent('notify', [
                'title' => __('Revoke Permission'),
                'message' => __('Permission :permission has been revoked to this role.', ['permission' => $permission->display_name]),
            ]);
        } else {
            $this->role->givePermissionTo($permission->name);
            $this->dispatchBrowserEvent('notify', [
                'title' => __('Allow Permission'),
                'message' => __('Permission :permission has been given to this role.', ['permission' => $permission->display_name]),
            ]);
        }
    }

    public function render()
    {
        return view('shopper::livewire.settings.management.permissions', [
            'groupPermissions' => Permission::query()
                ->with('users')
                ->orderBy('created_at')
                ->get()
                ->groupBy('group_name')
        ]);
    }
}
