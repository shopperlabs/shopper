<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Modals;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;

class DeleteMailable extends ModalComponent
{
    public string $item;

    public function mount(string $item): void
    {
        $this->item = $item;
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function delete(): void
    {
        $mailableFile = config('shopper.mails.mailables_dir') . $this->item . '.php';

        if (file_exists($mailableFile)) {
            unlink($mailableFile);

            Notification::make()
                ->title(__('Removed'))
                ->body(__('You have removed the :class', ['class' => $this->item]))
                ->success()
                ->send();
        } else {
            session()->flash('error', __("This file don't exist."));
        }

        $this->emit('onMailableAction');

        $this->closeModal();
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.delete-mailable');
    }
}
