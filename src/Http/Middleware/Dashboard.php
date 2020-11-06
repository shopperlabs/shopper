<?php

namespace Shopper\Framework\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Shopper\Framework\Models\System\Setting;

class Dashboard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        // Check if the user is super admin or have to ability to access to the backend
        if (!$user->isAdmin()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response(__('Unauthorized'), Response::HTTP_UNAUTHORIZED);
            }

            return abort(403, __("Unauthorized"));
        }


        if (!Setting::query()->where('key', 'shop_email')->exists()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response(__("Unauthorized"), Response::HTTP_UNAUTHORIZED);
            }

            return redirect()->route('shopper.initialize');
        }

        return $next($request);
    }
}
