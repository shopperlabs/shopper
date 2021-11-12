<?php

namespace Shopper\Framework\Http\Livewire\Settings\Mails;

use Livewire\Component;
use Shopper\Framework\Services\Mailable;

class Mailables extends Component
{
    public bool $isLocal = false;

    protected $listeners = ['onMailableAction' => 'render'];

    public function mount()
    {
        if (in_array(app()->environment(), config('shopper.mails.allowed_environments'))) {
            $this->isLocal = true;
        }
    }

    public function render()
    {
        return view('shopper::livewire.settings.mails.mailables', [
            'mailables' => (null !== $mailables = Mailable::getMailables())
                ? $mailables->sortBy('name')
                : collect([]),
        ]);
    }
}
