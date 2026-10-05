<?php

declare(strict_types=1);

namespace Shopper\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ResolveCustomer
{
    public function handle(Request $request, Closure $next): Response
    {
        $customer = $request->user('sanctum');

        if ($customer !== null && ! $this->isStoreCustomer($customer)) {
            throw new AuthenticationException;
        }

        $request->attributes->set('shopper_customer', $customer);

        return $next($request);
    }

    private function isStoreCustomer(Authenticatable $customer): bool
    {
        $model = (string) config('auth.providers.users.model');

        return $customer instanceof $model && method_exists($customer, 'tokenCan') && $customer->tokenCan('store');
    }
}
