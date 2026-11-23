<?php

declare(strict_types=1);

namespace Shopper\Traits;

use Shopper\Models\Contracts\ShopperUser;

trait AuthorizesSettingsAccess
{
    protected function authorizeSettingsAccess(): void
    {
        abort_unless($this->canAccessSettings(), 403);
    }

    protected function canAccessSettings(): bool
    {
        $user = shopper()->auth()->user();

        return $user instanceof ShopperUser && ($user->isAdmin() || $user->can('system.settings'));
    }
}
