<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Domain;

use Illuminate\Contracts\Container\Container;
use Serializable;
use Shopper\Sidebar\Contracts\Builder\Append;
use Shopper\Sidebar\Traits\AuthorizableTrait;
use Shopper\Sidebar\Traits\CacheableTrait;
use Shopper\Sidebar\Traits\RouteableTrait;

class DefaultAppend implements Append, Serializable
{
    use CacheableTrait;
    use RouteableTrait;
    use AuthorizableTrait;

    protected ?string $name = null;

    protected string $icon = 'heroicon-s-plus-small';

    protected string $type = 'blade';

    protected array $cacheables = [
        'name',
        'url',
        'icon',
        'type',
    ];

    public function __construct(protected Container $container)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Append
    {
        $this->name = $name;

        return $this;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): Append
    {
        $this->icon = $icon;

        return $this;
    }
}
