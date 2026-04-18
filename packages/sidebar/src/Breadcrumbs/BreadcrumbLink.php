<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Breadcrumbs;

final class BreadcrumbLink
{
    public readonly string $url;

    public function __construct(
        public readonly string $text,
        string $url,
        public readonly ?string $icon = null,
        public readonly bool $spa = true,
    ) {
        $this->url = UrlSanitizer::sanitize($url) ?? '#';
    }
}
