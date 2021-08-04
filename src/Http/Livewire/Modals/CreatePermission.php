<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Models\User\Role;
use Shopper\Framework\Models\User\Permission;

class CreatePermission extends ModalComponent
{
    public int $roleId;

    public string $name = '';

    public string $display_name = '';

    public string $description = '';

    public ?string $group = null;

    public function mount(int $id)
    {
        $this->roleId = $id;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|max:50|unique:permissions,name',
            'display_name' => 'required|max:75',
        ]);

        $permission = Permission::query()->create([
            'name' => $this->name,
            'group_name' => $this->group,
            'display_name' => $this->display_name,
            'description' => $this->description,
        ]);

        Role::findById($this->roleId)->givePermissionTo($permission->name);

        $this->dispatchBrowserEvent('permission-added');

        $this->notify([
            'title' => __('Saved'),
            'message' => __('A new permission has been create and add to this role.'),
        ]);

        $this->emit('permissionAdded', $this->roleId);

        $this->closeModal();
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function render()
    {
        return view('shopper::livewire.modals.create-permission', [
            'groups' => Permission::groups(),
        ]);
    }
}
