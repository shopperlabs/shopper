<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Shopper\Core\Models\Role;

class DeleteRole extends ModalComponent
{
    public int $roleId;

    public function mount(int $id): void
    {
        $this->roleId = $id;
    }

    public function delete(): void
    {
        Role::query()->find($this->roleId)->delete();

        session()->flash('success', __('shopper::notifications.users_roles.role_deleted'));

        $this->redirectRoute('shopper.settings.users');
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.delete-role');
    }
}
