<?php

declare(strict_types=1);

namespace Shopper\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Shopper\Framework\Models\System\Setting;

class Dashboard
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth(config('shopper.auth.guard'))->user();

        if (! $user->isAdmin() && ! $user->hasPermissionTo('access_dashboard')) {
            abort(403, __('Unauthorized'));
        }

        if (! Setting::query()->where('key', 'shop_email')->exists()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response(__('Unauthorized'), Response::HTTP_UNAUTHORIZED);
            }

            return redirect()->route('shopper.initialize');
        }

        return $next($request);
    }
}
