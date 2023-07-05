<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Domain;

use Illuminate\Container\Container;
use Serializable;
use Shopper\Sidebar\Contracts\Builder\Badge;
use Shopper\Sidebar\Traits\AuthorizableTrait;
use Shopper\Sidebar\Traits\CacheableTrait;
use Shopper\Sidebar\Traits\CallableTrait;

final class DefaultBadge implements Badge, Serializable
{
    use CallableTrait;
    use CacheableTrait;
    use AuthorizableTrait;

    protected ?string $value = null;

    protected string $class = 'badge-sidebar';

    protected array $cacheables = [
        'value',
        'class',
    ];

    public function __construct(protected Container $container)
    {
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }
}
