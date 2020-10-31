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
                'title' => 'Revoke Permission',
                'message' => __("Permission $permission->display_name has been revoked to this role."),
            ]);
        } else {
            $this->role->givePermissionTo($permission->name);
            $this->dispatchBrowserEvent('notify', [
                'title' => 'Allow Permission',
                'message' => __("Permission $permission->display_name has been given to this role."),
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
