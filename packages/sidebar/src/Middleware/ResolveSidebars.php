<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Middleware;

use Closure;
use Illuminate\Http\Request;
use Shopper\Sidebar\SidebarManager;

class ResolveSidebars
{
    public function __construct(protected SidebarManager $sidebarManager)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        $this->sidebarManager->resolve();

        return $next($request);
    }
}
