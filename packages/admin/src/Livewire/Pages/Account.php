<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages;

use Illuminate\Contracts\View\View;
use Shopper\Traits\HandlesAuthorizationExceptions;

class Account extends AbstractPageComponent
{
    use HandlesAuthorizationExceptions;

    public function render(): View
    {
        return view('shopper::livewire.pages.account')
            ->title(__('shopper::pages/auth.account.meta_title'));
    }
}
