<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages;

use Illuminate\Contracts\View\View;

class Account extends AbstractPageComponent
{
    public function render(): View
    {
        return view('shopper::livewire.pages.account')
            ->title(__('shopper::pages/auth.account.meta_title'));
    }
}
