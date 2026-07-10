<?php

declare(strict_types=1);

namespace Shopper\Core\Import\Contracts;

use Illuminate\Support\LazyCollection;
use Shopper\Core\Import\ProductRow;

interface ImportSource
{
    public function code(): string;

    public function name(): string;

    public function description(): string;

    public function icon(): string;

    public function isConfigured(): bool;

    /**
     * @return LazyCollection<int, ProductRow>
     */
    public function read(string $path): LazyCollection;
}
