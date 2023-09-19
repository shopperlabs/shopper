<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Settings\Mails;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Framework\Services\Mailable;

class AddTemplate extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.settings.mails.templates.add-template', [
            'skeletons' => Mailable::getTemplateSkeletons(),
        ]);
    }
}
