<?php

declare(strict_types=1);

namespace Shopper\Framework\Exceptions;

use Illuminate\Auth\AuthenticationException as BaseAuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AuthenticationException extends BaseAuthenticationException
{
    public function render(Request $request): JsonResponse|RedirectResponse
    {
        return $request->expectsJson()
            ? response()->json(['message' => $this->getMessage()], 401)
            : redirect()->guest($this->location());
    }

    protected function location(): string
    {
        if (Route::getRoutes()->hasNamedRoute('shopper.login')) {
            return route('shopper.login');
        }

        if (Route::getRoutes()->hasNamedRoute('login')) {
            return route('login');
        }

        return '/login';
    }
}
