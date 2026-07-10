<?php

declare(strict_types=1);

namespace Shopper\Core\Import\Contracts;

interface SupportsColumnMapping
{
    /**
     * @return array<int, string>
     */
    public function headers(string $path): array;

    /**
     * @param  array<string, string>  $mapping  Shopper field => file column
     */
    public function withMapping(array $mapping): static;
}
