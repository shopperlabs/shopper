<?php

declare(strict_types=1);

namespace Shopper\Http\Support;

final class DashboardAccess
{
    public static function granted(object $user): bool
    {
        return method_exists($user, 'canAccessDashboard') && $user->canAccessDashboard();
    }
}
