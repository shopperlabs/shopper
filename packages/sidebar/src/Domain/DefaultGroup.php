<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Domain;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;
use Serializable;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Traits\AuthorizableTrait;
use Shopper\Sidebar\Traits\CacheableTrait;
use Shopper\Sidebar\Traits\CallableTrait;
use Shopper\Sidebar\Traits\ItemableTrait;

class DefaultGroup implements Group, Serializable
{
    use AuthorizableTrait;
    use CacheableTrait;
    use CallableTrait;
    use ItemableTrait;

    protected ?string $name = null;

    protected int $weight = 0;

    protected bool $heading = true;

    protected ?string $class = null;

    protected ?string $itemClass = null;

    protected ?string $headingClass = null;

    protected array $cacheables = [
        'name',
        'items',
        'weight',
        'heading',
    ];

    public function __construct(protected Container $container)
    {
        $this->items = new Collection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Group
    {
        $this->name = $name;

        return $this;
    }

    public function weight(int $weight): Group
    {
        $this->weight = $weight;

        return $this;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function hideHeading(bool $hide = true): Group
    {
        $this->heading = ! $hide;

        return $this;
    }

    public function shouldShowHeading(): bool
    {
        return $this->heading;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(string $class): Group
    {
        $this->class = $class;

        return $this;
    }

    public function getGroupItemsClass(): ?string
    {
        return $this->itemClass;
    }

    public function setGroupItemsClass(string $class): Group
    {
        $this->itemClass = $class;

        return $this;
    }

    public function getHeadingClass(): ?string
    {
        return $this->headingClass;
    }

    public function setHeadingClass(string $class): Group
    {
        $this->headingClass = $class;

        return $this;
    }
}
