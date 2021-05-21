<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;

class DeleteMailable extends ModalComponent
{
    public string $item;

    public function mount(string $item)
    {
        $this->item = $item;
    }

    public function delete()
    {
        $mailableFile = config('shopper.mails.mailables_dir').$this->item.'.php';

        if (file_exists($mailableFile)) {
            unlink($mailableFile);

            $this->notify([
                'title' => __('Removed'),
                'message' => __("You have removed the :class", ['class' => $this->item])
            ]);
        } else {
            session()->flash('error', __("This file don't exist."));
        }

        $this->emit('onMailableAction');

        $this->closeModal();
    }

    public function render()
    {
        return view('shopper::livewire.modals.delete-mailable');
    }
}
