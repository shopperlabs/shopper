<?php

declare(strict_types=1);

namespace Shopper\Core\Exceptions;

use RuntimeException;

final class LazyStockLoadingException extends RuntimeException
{
    public function __construct(string $model)
    {
        parent::__construct(
            "Attempted to lazy load stock on model [{$model}] but lazy stock loading is disabled. "
            ."Use [{$model}::loadCurrentStock(\$collection)] to batch-load stock before accessing \$model->stock."
        );
    }
}
