<?php

namespace Shopper\Framework\Http\Livewire\Settings\Management;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Shopper\Framework\Models\User\Permission;
use Shopper\Framework\Models\User\Role as RoleModel;

class Role extends Component
{
    public RoleModel $role;
    public string $name = '';
    public string $display_name = '';
    public string $description = '';

    public string $permission_name = '';
    public string $permission_display_name = '';
    public string $permission_description = '';
    public $group;

    public function mount(RoleModel $role)
    {
        $this->name = $role->name;
        $this->display_name = $role->display_name;
        $this->description = $role->description;
    }

    public function save()
    {
        $this->validate([
            'name' => [
                'required',
                Rule::unique('roles', 'name')->ignore($this->role->id)
            ],
        ], [
            'name.required' => 'The role name is required.',
            'name.unique' => 'This name is already used.'
        ]);

        RoleModel::query()->find($this->role->id)->update([
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
        ]);

        $this->dispatchBrowserEvent('notify', [
            'title' => 'Updated',
            'message' => __("Role updated successfully!"),
        ]);
    }

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
        $this->dispatchBrowserEvent('notify', [
            'title' => __('Saved'),
            'message' => __("A new permission has been create and add to this role.")
        ]);

        $this->emit('permissionAdded');
    }

    public function destroy()
    {
        RoleModel::query()->find($this->role->id)->delete();

        session()->flash('success', "Role deleted successfully.");
        $this->redirectRoute('shopper.settings.users');
    }

    public function render()
    {
        return view('shopper::livewire.settings.management.role', [
            'groups' => Permission::groups()
        ]);
    }
}
