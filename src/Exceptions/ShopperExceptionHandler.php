<?php

namespace Shopper\Framework\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class ShopperExceptionHandler extends ExceptionHandler
{
    /**
     * Report or log an exception.
     *
     * @throws Throwable
     */
    public function report(Throwable $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($request->user() && $request->user()->hasRole(config('shopper.system.users.admin_role'))) {
            return response()->view(
                'shopper::errors.template',
                ['exception' => $e]
            );
        }

        return parent::render($request, $e);
    }
}
