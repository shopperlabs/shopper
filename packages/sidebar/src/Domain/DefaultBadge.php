<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Domain;

use Illuminate\Container\Container;
use Serializable;
use Shopper\Sidebar\Contracts\Builder\Badge;
use Shopper\Sidebar\Traits\AuthorizableTrait;
use Shopper\Sidebar\Traits\CacheableTrait;

class DefaultBadge implements Badge, Serializable
{
    use AuthorizableTrait;
    use CacheableTrait;

    protected mixed $value = null;

    protected string $class = '';

    protected ?string $color = null;

    /** @var string[] */
    protected array $cacheables = [
        'value',
        'class',
        'color',
        'authorized',
    ];

    public function __construct(protected Container $container) {}

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): self
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function color(string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
