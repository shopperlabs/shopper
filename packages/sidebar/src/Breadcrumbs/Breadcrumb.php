<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Breadcrumbs;

final class Breadcrumb
{
    public readonly ?string $url;

    /**
     * @param  list<BreadcrumbLink>|null  $links
     */
    public function __construct(
        public readonly string $text,
        ?string $url = null,
        public readonly ?string $icon = null,
        public readonly ?array $links = null,
        public readonly bool $spa = true,
    ) {
        $this->url = UrlSanitizer::sanitize($url);
    }
}
