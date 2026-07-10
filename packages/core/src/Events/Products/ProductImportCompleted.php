<?php

declare(strict_types=1);

namespace Shopper\Core\Events\Products;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Shopper\Core\Models\ProductImport;

final class ProductImportCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ProductImport $import
    ) {}
}
