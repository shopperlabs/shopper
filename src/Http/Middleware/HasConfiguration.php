<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Shopper\Framework\Models\System\Setting;

class HasConfiguration
{
    public function handle(Request $request, Closure $next)
    {
        if (Setting::query()->where('key', 'shop_email')->exists()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['setup' => true]);
            }

            return redirect()->route('shopper.dashboard');
        }

        return $next($request);
    }
}
