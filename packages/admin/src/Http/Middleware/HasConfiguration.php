<?php

declare(strict_types=1);

namespace Shopper\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Shopper\Core\Models\Setting;

class HasConfiguration
{
    public function handle(Request $request, Closure $next)
    {
        if (Setting::query()->where(function (Builder $query): void {
            $query->where('key', 'street_address')
                ->where('key', 'email');
        })->exists()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['setup' => true]);
            }

            return redirect()->route('shopper.dashboard');
        }

        return $next($request);
    }
}
