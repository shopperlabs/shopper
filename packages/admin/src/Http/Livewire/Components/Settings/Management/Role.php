<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Settings\Management;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Shopper\Core\Models\Role as RoleModel;

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
                Rule::unique(config('permission.table_names')['roles'], 'name')->ignore($this->role->id),
            ],
        ]);

        RoleModel::query()->find($this->role->id)->update([
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
        ]);

        Notification::make()
            ->body(__('shopper::notifications.actions.update', ['item' => __('shopper::words.role')]))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.settings.management.role');
    }
}
