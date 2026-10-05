<?php

declare(strict_types=1);

namespace Shopper\Core\Exceptions;

use RuntimeException;
use Throwable;

final class PaymentProviderUnavailableException extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(__('shopper-core::exceptions.payment_provider_unavailable'), previous: $previous);
    }
}
