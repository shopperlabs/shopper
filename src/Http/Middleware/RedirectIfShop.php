<?php

namespace Shopper\Framework\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class RedirectIfShop
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

        if ($user->shop || $user->shopMember) {
            return redirect()->route(home_route());
        }

        return $next($request);
    }
}
