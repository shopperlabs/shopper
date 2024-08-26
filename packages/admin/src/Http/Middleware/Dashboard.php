<?php

declare(strict_types=1);

namespace Shopper\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Shopper\Core\Models\User;
use Shopper\Facades\Shopper;

class Dashboard
{
    public function handle(Request $request, Closure $next)
    {
        /** @var User $user */
        $user = Shopper::auth()->user();

        if (! $user->isAdmin() && ! $user->hasPermissionTo('access_dashboard')) {
            abort(403, __('Unauthorized'));
        }

        if (is_null(shopper_setting('email')) || is_null(shopper_setting('street_address'))) {
            if ($request->ajax() || $request->wantsJson()) {
                return response(__('Unauthorized'), Response::HTTP_UNAUTHORIZED);
            }

            return redirect()->route('shopper.initialize');
        }

        return $next($request);
    }
}
