<?php

declare(strict_types=1);

namespace Shopper\Core\Import;

final readonly class VariantRow
{
    /**
     * @param  array<string, string>  $options
     */
    public function __construct(
        public array $options = [],
        public ?string $sku = null,
        public ?string $barcode = null,
        public ?string $ean = null,
        public ?string $upc = null,
        public ?float $price = null,
        public ?float $compareAtPrice = null,
        public ?float $costPerItem = null,
        public ?string $currency = null,
        public int $quantity = 0,
        public ?float $weightValue = null,
        public ?string $weightUnit = null,
        public bool $requiresShipping = true,
        public bool $allowBackorder = false,
        public ?string $imageUrl = null,
    ) {}

    public function name(string $fallback): string
    {
        $values = array_values(array_filter($this->options));

        return $values === [] ? $fallback : implode(' / ', $values);
    }
}
