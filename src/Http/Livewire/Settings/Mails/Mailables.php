<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Settings\Mails;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Framework\Services\Mailable;

class Mailables extends Component
{
    public bool $isLocal = false;

    protected $listeners = ['onMailableAction' => '$refresh'];

    public function mount(): void
    {
        if (in_array(app()->environment(), config('shopper.mails.allowed_environments'))) {
            $this->isLocal = true;
        }
    }

    public function render(): View
    {
        return view('shopper::livewire.settings.mails.mailables', [
            'mailables' => (null !== $mailables = Mailable::getMailables())
                ? $mailables->sortBy('name')
                : collect([]),
        ]);
    }
}
