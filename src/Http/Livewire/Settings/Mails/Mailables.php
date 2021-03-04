<?php

namespace Shopper\Framework\Http\Livewire\Settings\Mails;

use Livewire\Component;

class Mailables extends Component
{
    /**
     * Component Mount instance.
     */
    public function mount()
    {

    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.settings.mails.mailables');
    }
}
