<?php

declare(strict_types=1);

namespace Shopper\Core\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;

final class ShopperException extends Exception
{
    /**
     * Error Message.
     *
     * @var string
     */
    public $message;

    public function render(): RedirectResponse
    {
        return redirect()
            ->back()
            ->withInput()
            ->with('danger', $this->message);
    }
}
