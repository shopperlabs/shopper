<?php

declare(strict_types=1);

namespace Shopper\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class ShopperExceptionHandler extends ExceptionHandler
{
    /**
     * Report or log an exception.
     *
     * @throws Throwable
     */
    public function report(Throwable $e): void
    {
        parent::report($e);
    }

    public function render($request, Throwable $e): Response
    {
        // @phpstan-ignore-next-line
        if ($request->user() && $request->user()->hasRole(config('shopper.core.users.admin_role'))) {
            return response()->view(
                'shopper::errors.template',
                ['exception' => $e]
            );
        }

        return parent::render($request, $e);
    }
}
