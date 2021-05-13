<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Services\Mailable;

class DeleteTemplate extends ModalComponent
{
    public string $name;
    public string $slug;

    public function mount(string $name, string $slug)
    {
        $this->name = $name;
        $this->slug = $slug;
    }

    public function delete()
    {
        Mailable::deleteTemplate($this->slug);

        $this->notify([
            'title' => __('Removed'),
            'message' => __('You have removed the :template', ['template' => $this->name])
        ]);

        $this->emit('onTemplateRemoved');

        $this->closeModal();
    }

    public function render()
    {
        return view('shopper::livewire.modals.delete-mailable');
    }
}
