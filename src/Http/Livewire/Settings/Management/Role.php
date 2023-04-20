<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Settings\Management;

use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Shopper\Framework\Models\User\Role as RoleModel;
use WireUi\Traits\Actions;

class Role extends Component
{
    use Actions;

    public RoleModel $role;

    public string $name;

    public string $display_name = '';

    public ?string $description = null;

    public function mount(RoleModel $role): void
    {
        $this->name = $role->name;
        $this->display_name = $role->display_name;
        $this->description = $role->description;
    }

    public function save(): void
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

        $this->notification()->success(__('Updated'), __('Role updated successfully!'));
    }

    public function render(): View
    {
        return view('shopper::livewire.settings.management.role');
    }
}
