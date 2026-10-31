<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages;

use Illuminate\Contracts\View\View;
use Laravel\Passkeys\Contracts\PasskeyUser;
use Shopper\Contracts\HasStoreAuthentication;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Sidebar\Traits\WithBreadcrumbs;
use Shopper\Traits\HandlesAuthorizationExceptions;

class Account extends AbstractPageComponent
{
    use HandlesAuthorizationExceptions;
    use WithBreadcrumbs;

    public function getBreadcrumbs(): array
    {
        return [
            new Breadcrumb(text: __('shopper::pages/auth.account.title')),
        ];
    }

    public function render(): View
    {
        $user = shopper()->auth()->user();

        return view('shopper::livewire.pages.account', [
            'twoFactorEnabled' => config('shopper.auth.2fa_enabled') && $user instanceof HasStoreAuthentication,
            'passkeysEnabled' => config('shopper.auth.passkeys_enabled') && $user instanceof PasskeyUser,
        ])->title(__('shopper::pages/auth.account.meta_title'));
    }
}
