<?php

declare(strict_types=1);

namespace Shopper\Cart\Exceptions;

use RuntimeException;
use Shopper\Cart\Models\CartLine;

final class CartLineMetadataConflictException extends RuntimeException
{
    public function __construct(
        public readonly CartLine $cartLine,
    ) {
        parent::__construct(__('shopper-cart::exceptions.line_metadata_conflict'));
    }
}
