<?php

declare(strict_types=1);

namespace Shopper\Livewire\Modals;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Shopper\Core\Models\Role;

class CreateRole extends ModalComponent
{
    public string $name = '';

    public string $display_name = '';

    public string $description = '';

    public function save(): void
    {
        $this->validate(['name' => 'required|unique:' . config('permission.table_names')['roles']]);

        Role::create([
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
        ]);

        $this->dispatch('teamUpdate');

        Notification::make()
            ->body(__('shopper::notifications.users_roles.role_added'))
            ->success()
            ->send();

        $this->closeModal();
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.create-role');
    }
}
