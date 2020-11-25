<?php

namespace Shopper\Framework\Exceptions;

use Exception;

class GeneralException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->view(
            'shopper::errors.template',
            ['exception' => $this]
        );
    }
}
