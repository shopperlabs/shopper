<?php

declare(strict_types=1);

namespace Shopper\Core\Import;

final readonly class ImageRow
{
    public function __construct(
        public string $url,
        public int $position = 1,
        public ?string $alt = null,
    ) {}
}
