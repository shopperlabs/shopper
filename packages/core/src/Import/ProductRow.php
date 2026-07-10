<?php

declare(strict_types=1);

namespace Shopper\Core\Import;

final readonly class ProductRow
{
    /**
     * @param  array<int, string>  $categories
     * @param  array<int, string>  $tags
     * @param  array<int, string>  $optionNames
     * @param  array<int, VariantRow>  $variants
     * @param  array<int, ImageRow>  $images
     */
    public function __construct(
        public string $handle,
        public string $name,
        public ?string $description = null,
        public ?string $brand = null,
        public array $categories = [],
        public array $tags = [],
        public bool $published = false,
        public ?string $seoTitle = null,
        public ?string $seoDescription = null,
        public array $optionNames = [],
        public array $variants = [],
        public array $images = [],
    ) {}

    public function isStandard(): bool
    {
        return count($this->variants) <= 1 && $this->optionNames === [];
    }
}
