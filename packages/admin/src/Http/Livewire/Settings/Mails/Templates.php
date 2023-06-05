<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Settings\Mails;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Services\Mailable;

class Templates extends Component
{
    public bool $isLocal = false;

    protected $listeners = ['onTemplateRemoved' => '$refresh'];

    public function mount(): void
    {
        $this->isLocal = true;
    }

    public function render(): View
    {
        return view('shopper::livewire.settings.mails.templates.browse', [
            'templates' => Mailable::getTemplates(),
        ]);
    }
}
