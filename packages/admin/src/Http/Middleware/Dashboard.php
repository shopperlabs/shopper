<?php

declare(strict_types=1);

namespace Shopper\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Shopper\Facades\Shopper;
use Shopper\Models\Contracts\ShopperUser;
use Spatie\Permission\Contracts\Permission;

class Dashboard
{
    public function handle(Request $request, Closure $next): mixed
    {
        /** @var ShopperUser&Permission $user */
        $user = Shopper::auth()->user();

        abort_if(! $user->isAdmin() && ! $user->hasPermissionTo('access_dashboard'), 403, __('Unauthorized'));

        if (blank(shopper_setting('email')) || blank(shopper_setting('street_address'))) {
            if ($request->ajax() || $request->wantsJson()) {
                return response(__('Unauthorized'), Response::HTTP_UNAUTHORIZED);
            }

            return redirect()->route('shopper.initialize');
        }

        return $next($request);
    }
}
