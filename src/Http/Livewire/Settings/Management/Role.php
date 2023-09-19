<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Settings\Management;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Shopper\Framework\Models\User\Role as RoleModel;

class Role extends Component
{
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

        Notification::make()
            ->title(__('shopper::components.tables.status.updated'))
            ->body(__('Role updated successfully'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.settings.management.role');
    }
}
