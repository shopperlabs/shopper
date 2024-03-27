<?php

declare(strict_types=1);

namespace Shopper\Livewire\Modals;

use Filament\Notifications\Notification;
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

        Notification::make()
            ->title(__('shopper::notifications.users_roles.role_deleted'))
            ->success()
            ->send();

        $this->redirectRoute(name: 'shopper.settings.users', navigate: true);
    }

    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.delete-role');
    }
}
