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
    use AuthorizableTrait;
    use CacheableTrait;
    use RouteableTrait;

    protected ?string $name = null;

    protected string $icon = 'untitledui-plus';

    protected string $iconClass = '';

    protected string $type = 'blade';

    protected ?string $class = null;

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

    public function getClass(): string
    {
        return $this->class;
    }

    public function setClass(string $className): Append
    {
        $this->class = $className;

        return $this;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon, string $type = 'blade', string $iconClass = ''): Append
    {
        $this->icon = $icon;
        $this->type = $type;
        $this->iconClass = $iconClass;

        return $this;
    }

    public function getIconClass(): string
    {
        return $this->iconClass;
    }

    public function iconSvg(): bool
    {
        return 'svg' === $this->type;
    }
}
