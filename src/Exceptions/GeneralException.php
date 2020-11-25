<?php

namespace Shopper\Framework\Exceptions;

use Exception;
use Throwable;

class GeneralException extends Exception
{
    /**
     * Error Message.
     *
     * @var string
     */
    public $message;

    /**
     * GeneralException constructor.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  Throwable|null  $previous
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Report the exception.
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function render($request)
    {
        return redirect()
            ->back()
            ->withInput()
            ->with('danger', $this->message);
    }
}
