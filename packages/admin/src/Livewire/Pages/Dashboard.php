<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages;

use Illuminate\Contracts\View\View;

class Dashboard extends AbstractPageComponent
{
    public function render(): View
    {
        return view('shopper::livewire.pages.dashboard')
            ->title(__('shopper::pages/dashboard.menu'));
    }
}
