<?php

namespace Shopper\Framework\Http\Livewire\Settings\Management;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Shopper\Framework\Models\User\Role as RoleModel;

class Role extends Component
{
    public RoleModel $role;

    public string $name;

    public string $display_name = '';

    public ?string $description = null;

    /**
     * Component Mount instance.
     */
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
                Rule::unique('roles', 'name')->ignore($this->role->id),
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
            'message' => __('Role updated successfully!'),
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.settings.management.role');
    }
}
