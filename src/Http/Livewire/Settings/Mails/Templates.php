<?php

namespace Shopper\Framework\Http\Livewire\Settings\Mails;

use Livewire\Component;
use Shopper\Framework\Services\Mailable;

class Templates extends Component
{
    protected $listeners = ['onTemplateRemoved' => 'render'];

    public bool $isLocal = false;

    /**
     * Component Mount instance.
     *
     * @return void
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
            'templates' => Mailable::getTemplates()
        ]);
    }
}
