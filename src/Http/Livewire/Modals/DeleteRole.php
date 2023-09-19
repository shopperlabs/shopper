<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Models\User\Role;

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

        session()->flash('success', __('Role deleted successfully.'));

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
