<?php

declare(strict_types=1);

namespace Shopper\Livewire\Modals;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Shopper\Core\Models\Permission;
use Shopper\Core\Models\Role;

class CreatePermission extends ModalComponent
{
    public int $roleId;

    public string $name = '';

    public string $display_name = '';

    public string $description = '';

    public ?string $group = null;

    public function mount(int $id): void
    {
        $this->roleId = $id;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|max:50|unique:permissions,name',
            'display_name' => 'required|max:75',
        ]);

        /** @var Permission $permission */
        $permission = Permission::query()->create([
            'name' => $this->name,
            'group_name' => $this->group,
            'display_name' => $this->display_name,
            'description' => $this->description,
        ]);

        Role::findById($this->roleId)->givePermissionTo($permission->name);

        $this->dispatch('permission-added');

        Notification::make()
            ->title(__('shopper::notifications.users_roles.permission_add'))
            ->success()
            ->send();

        $this->dispatch('permissionAdded');

        $this->closeModal();
    }

    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.create-permission', [
            'groups' => Permission::groups(),
        ]);
    }
}
