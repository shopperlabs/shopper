<?php

declare(strict_types=1);

namespace Shopper\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Shopper\Facades\Shopper;
use Shopper\Models\Contracts\ShopperUser;

class Dashboard
{
    public function handle(Request $request, Closure $next): mixed
    {
        /** @var ShopperUser $user */
        $user = Shopper::auth()->user();

        abort_unless($user->canAccessDashboard(), 403, __('shopper::notifications.unauthorized.title'));

        if (blank(shopper_setting('email')) || blank(shopper_setting('street_address'))) {
            if ($request->ajax() || $request->wantsJson()) {
                return response(__('shopper::notifications.unauthorized.title'), Response::HTTP_UNAUTHORIZED);
            }

            return redirect()->route('shopper.initialize');
        }

        return $next($request);
    }
}
