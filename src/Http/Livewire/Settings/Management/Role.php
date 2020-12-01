<?php

namespace Shopper\Framework\Http\Livewire\Settings\Management;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Shopper\Framework\Models\User\Permission;
use Shopper\Framework\Models\User\Role as RoleModel;

class Role extends Component
{
    /**
     * Role Model.
     *
     * @var RoleModel
     */
    public RoleModel $role;

    /**
     * Role name.
     *
     * @var string
     */
    public $name = '';

    /**
     * Role display name.
     *
     * @var string
     */
    public $display_name = '';

    /**
     * Role description.
     *
     * @var string
     */
    public $description = '';

    /**
     * Permission Name.
     *
     * @var string
     */
    public $permission_name = '';

    /**
     * Permission display name.
     *
     * @var string
     */
    public $permission_display_name = '';

    /**
     * Permission description.
     *
     * @var string
     */
    public $permission_description = '';

    /**
     * Permission group item.
     *
     * @var string
     */
    public $group;

    /**
     * Component Mount instance.
     *
     * @param  RoleModel  $role
     * @return void
     */
    public function mount(RoleModel $role)
    {
        $this->name = $role->name;
        $this->display_name = $role->display_name;
        $this->description = $role->description;
    }

    /**
     * Save a new record in the storage.
     *
     * @return void
     */
    public function save()
    {
        $this->validate([
            'name' => [
                'required',
                Rule::unique('roles', 'name')->ignore($this->role->id)
            ],
        ], [
            'name.required' => __('The role name is required.'),
            'name.unique' => __('This name is already used.'),
        ]);

        RoleModel::query()->find($this->role->id)->update([
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
        ]);

        $this->notify([
            'title' => __('Updated'),
            'message' => __("Role updated successfully!"),
        ]);
    }

    /**
     * Add a new permission on the storage.
     *
     * @return void
     */
    public function addPermission()
    {
        $this->validate([
            'permission_name' => 'required|max:20|unique:permissions,name'
        ]);

        $permission = Permission::query()->create([
            'name' => $this->permission_name,
            'group_name' => $this->group,
            'display_name' => $this->permission_display_name,
            'description' => $this->permission_description,
        ]);

        $this->role->givePermissionTo($permission->name);

        $this->dispatchBrowserEvent('permission-added');
        $this->notify([
            'title' => __('Saved'),
            'message' => __("A new permission has been create and add to this role.")
        ]);

        $this->emit('permissionAdded', $this->role->id);

        $this->group = null;
        $this->permission_name = '';
        $this->permission_display_name = '';
        $this->permission_description = '';
    }

    /**
     * Removed role item from the storage.
     *
     * @throws \Exception
     * @return void
     */
    public function destroy()
    {
        RoleModel::query()->find($this->role->id)->delete();

        session()->flash('success', __("Role deleted successfully."));
        $this->redirectRoute('shopper.settings.users');
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.settings.management.role', [
            'groups' => Permission::groups()
        ]);
    }
}
