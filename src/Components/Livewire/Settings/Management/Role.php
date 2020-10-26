<?php

namespace Shopper\Framework\Components\Livewire\Settings\Management;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Shopper\Framework\Models\User\Role as RoleModel;

class Role extends Component
{
    public RoleModel $role;
    public string $name = '';
    public string $display_name = '';
    public string $description = '';

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

    public function destroy()
    {
        RoleModel::query()->find($this->role->id)->delete();

        session()->flash('success', "Role deleted successfully.");
        $this->redirectRoute('shopper.settings.users');
    }

    public function render()
    {
        return view('shopper::livewire.settings.management.role');
    }
}
