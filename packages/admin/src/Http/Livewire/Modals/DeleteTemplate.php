<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Modals;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Services\Mailable;

class DeleteTemplate extends ModalComponent
{
    public string $name;

    public string $slug;

    public function mount(string $name, string $slug): void
    {
        $this->name = $name;
        $this->slug = $slug;
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function delete(): void
    {
        Mailable::deleteTemplate($this->slug);

        Notification::make()
            ->title(__('Removed'))
            ->body(__('You have removed the :template template', ['template' => $this->name]))
            ->success()
            ->send();

        $this->emit('onTemplateRemoved');

        $this->closeModal();
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.delete-template');
    }
}
