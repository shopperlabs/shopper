<?php

namespace Shopper\Framework\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Response;

class Dashboard
{
    /**
     * @var Guard
     */
    private $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $this->auth->user();
        // Check if the user is super admin or have to ability to access to the backend
        if (!$user->isSuperAdmin()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response(__('Unauthorized'), Response::HTTP_UNAUTHORIZED);
            }

            return abort(403, __("Unauthorized"));
        }

        if (!$user->shop && !$user->shopMember) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', Response::HTTP_UNAUTHORIZED);
            }

            return redirect()->route('shopper.shop.initialize');
        }

        return $next($request);
    }
}
