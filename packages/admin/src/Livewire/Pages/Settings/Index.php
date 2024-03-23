<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Illuminate\Contracts\View\View;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Index extends AbstractPageComponent
{
    public function render(): View
    {
        return view('shopper::livewire.pages.settings.index')
            ->title(__('shopper::words.settings'));
    }
}
