<?php

declare(strict_types=1);

namespace Shopper\Traits;

use Shopper\Models\Contracts\ShopperUser;

trait AuthorizesSettingsAccess
{
    protected function authorizeSettingsAccess(): void
    {
        $user = shopper()->auth()->user();

        abort_unless(
            $user instanceof ShopperUser && ($user->isAdmin() || $user->can('system.settings')),
            403,
        );
    }
}
