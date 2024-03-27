<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Traits;

trait RouteableTrait
{
    protected string $url = '#';

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function route(array | string $route, array $params = []): self
    {
        $this->setUrl(
            url: $this->container->make('url')->route($route, $params)
        );

        return $this;
    }
}
