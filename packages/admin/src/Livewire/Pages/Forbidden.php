<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages;

use Illuminate\Contracts\View\View;

class Forbidden extends AbstractPageComponent
{
    public function render(): View
    {
        return view('shopper::livewire.pages.forbidden')
            ->title(__('shopper::errors.403.title'));
    }
}
