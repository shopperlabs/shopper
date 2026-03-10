<?php

declare(strict_types=1);

namespace Shopper\Core\Media;

final readonly class MediaCollectionConfig
{
    /** @param array<string> $acceptsMimeTypes */
    public function __construct(
        public string $name,
        public string $disk = 'public',
        public bool $singleFile = false,
        public array $acceptsMimeTypes = [],
        public ?string $fallbackUrl = null,
    ) {}
}
