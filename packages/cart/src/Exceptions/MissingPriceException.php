<?php

declare(strict_types=1);

namespace Shopper\Cart\Exceptions;

use Illuminate\Database\Eloquent\Model;
use RuntimeException;

final class MissingPriceException extends RuntimeException
{
    public function __construct(
        public readonly ?Model $purchasable,
        public readonly string $currencyCode,
    ) {
        parent::__construct(__('shopper-cart::exceptions.missing_price', ['currency' => $currencyCode]));
    }
}
