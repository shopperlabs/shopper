<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Contracts;

interface Routeable
{
    public function getUrl(): string;

    public function setUrl(string $url): self;

    public function route(array|string $route, array $params = []): self;
}
