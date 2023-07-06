<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Contracts\Builder;

use Shopper\Sidebar\Contracts\Routeable;

interface Append extends Authorizable, Routeable
{
    public function getName(): string;

    public function setName(string $name): self;

    public function getIcon(): string;

    public function setIcon(string $icon): self;

    public function getUrl(): string;

    public function setUrl(string $url): self;

    public function route(array|string $route, array $params = []): self;
}
