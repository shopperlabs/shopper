<?php

namespace Shopper\Framework\Http\Livewire\Settings\Management;

use Livewire\Component;
use Shopper\Framework\Models\User\Permission;
use Shopper\Framework\Models\User\Role;

class Permissions extends Component
{
    /**
     * Role for the given permissions.
     *
     * @var Role
     */
    public Role $role;

    /**
     * Component listeners.
     *
     * @var string[]
     */
    protected $listeners = ['togglePermission', 'permissionAdded'];

    /**
     * Reload all Role permission in the view.
     *
     * @param  int  $id
     */
    public function permissionAdded(int  $id)
    {
        $this->role = Role::query()->findOrFail($id);
    }

    /**
     * Toggle permission on the role.
     *
     * @param  int  $id
     * @return void
     */
    public function togglePermission(int $id)
    {
        $permission = Permission::query()->find($id);

        if ($this->role->hasPermissionTo($permission->name)) {
            $this->role->revokePermissionTo($permission->name);
            $this->notify([
                'title' => __('Revoke Permission'),
                'message' => __('Permission :permission has been revoked to this role.', ['permission' => $permission->display_name]),
            ]);
        } else {
            $this->role->givePermissionTo($permission->name);
            $this->notify([
                'title' => __('Allow Permission'),
                'message' => __('Permission :permission has been given to this role.', ['permission' => $permission->display_name]),
            ]);
        }
    }

    /**
     * Removed a permission to the storage.
     *
     * @param  int  $id
     * @throws \Exception
     */
    public function removePermission(int $id)
    {
        Permission::query()->find($id)->delete();

        $this->notify([
            'title' => __('Deleted'),
            'message' => __("The permission has been correctly removed.")
        ]);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
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
