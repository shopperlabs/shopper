<?php

namespace Shopper\Framework\Http\Livewire\Settings\Mails;

use Livewire\Component;
use Shopper\Framework\Services\Mailable;

class AddTemplate extends Component
{
    public function render()
    {
        return view('shopper::livewire.settings.mails.templates.add-template', [
            'skeletons' => Mailable::getTemplateSkeletons(),
        ]);
    }
}
