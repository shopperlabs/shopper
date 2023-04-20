<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Services\Mailable;
use WireUi\Traits\Actions;

class DeleteTemplate extends ModalComponent
{
    use Actions;

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

        $this->notification()->success(__('Removed'), __('You have removed the :template template', ['template' => $this->name]));

        $this->emit('onTemplateRemoved');

        $this->closeModal();
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.delete-template');
    }
}
