<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages;

use Illuminate\Contracts\View\View;
use Shopper\Contracts\HasStoreAuthentication;
use Shopper\Traits\HandlesAuthorizationExceptions;

class Account extends AbstractPageComponent
{
    use HandlesAuthorizationExceptions;

    public function render(): View
    {
        return view('shopper::livewire.pages.account', [
            'twoFactorEnabled' => config('shopper.auth.2fa_enabled')
                && shopper()->auth()->user() instanceof HasStoreAuthentication,
        ])->title(__('shopper::pages/auth.account.meta_title'));
    }
}
