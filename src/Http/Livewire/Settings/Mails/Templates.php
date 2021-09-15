<?php

namespace Shopper\Framework\Http\Livewire\Settings\Mails;

use function in_array;
use Livewire\Component;
use Shopper\Framework\Services\Mailable;

class Templates extends Component
{
    public bool $isLocal = false;

    protected $listeners = ['onTemplateRemoved' => 'render'];

    /**
     * Component Mount instance.
     */
    public function mount()
    {
        if (in_array(app()->environment(), config('shopper.mails.allowed_environments'))) {
            $this->isLocal = true;
        }
    }

    public function render()
    {
        return view('shopper::livewire.settings.mails.templates.browse', [
            'templates' => Mailable::getTemplates(),
        ]);
    }
}
