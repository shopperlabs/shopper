<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Domain;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Serializable;
use Shopper\Sidebar\Contracts\Builder\Append;
use Shopper\Sidebar\Contracts\Builder\Badge;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Traits\AuthorizableTrait;
use Shopper\Sidebar\Traits\CacheableTrait;
use Shopper\Sidebar\Traits\CallableTrait;
use Shopper\Sidebar\Traits\ItemableTrait;
use Shopper\Sidebar\Traits\RouteableTrait;

final class DefaultItem implements Item, Serializable
{
    use CallableTrait;
    use CacheableTrait;
    use ItemableTrait;
    use RouteableTrait;
    use AuthorizableTrait;

    protected Collection|array $badges = [];

    protected Collection|array $appends = [];

    protected ?string $name = null;

    protected int $weight = 0;

    protected string $icon = 'heroicon-o-chevron-right';

    protected string $type = 'blade';

    protected string $toggleIcon = 'heroicon-o-chevron-down';

    protected bool $activeWhen = false;

    protected bool $newTab = false;

    protected string $itemClass = '';

    public function __construct(protected Container $container)
    {
        $this->items = new Collection();
        $this->badges = new Collection();
        $this->appends = new Collection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function name($name): Item
    {
        $this->name = $name;

        return $this;
    }

    public function weight($weight): Item
    {
        $this->weight = $weight;

        return $this;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon, string $type = 'blade'): Item
    {
        $this->icon = $icon;
        $this->type = $type;

        return $this;
    }

    public function getToggleIcon(): string
    {
        return $this->toggleIcon;
    }

    public function toggleIcon(string $icon): self
    {
        $this->toggleIcon = $icon;

        return $this;
    }

    public function isBladeType(): bool
    {
        return $this->type === 'blade';
    }

    public function badge(string|Closure $callbackOrValue = null, string $className = null): Badge
    {
        $badge = $this->container->make(Badge::class);

        if (is_callable($callbackOrValue)) {
            $this->call($callbackOrValue, $badge);
        } elseif ($callbackOrValue) {
            $badge->setValue($callbackOrValue);
        }

        if ($className) {
            $badge->setClass($className);
        }

        $this->addBadge($badge);

        return $badge;
    }

    public function addBadge(Badge $badge): Badge
    {
        $this->badges->push($badge);

        return $badge;
    }

    public function getBadges(): Collection | array
    {
        return $this->badges;
    }

    public function append($callbackOrUrl = null, string $icon = null, string $name = null): Append
    {
        $append = $this->container->make(Append::class);

        if (is_callable($callbackOrUrl)) {
            $this->call($callbackOrUrl, $append);
        } elseif ($callbackOrUrl) {
            $append->route($callbackOrUrl);
        }

        if ($name) {
            $append->name($name);
        }

        if ($icon) {
            $append->setIcon($icon);
        }

        $this->addAppend($append);

        return $append;
    }

    public function addAppend(Append $append): Append
    {
        $this->appends->push($append);

        return $append;
    }

    public function getAppends(): Collection | array
    {
        return $this->appends;
    }

    public function isActiveWhen(string $path): Item
    {
        $path = ltrim($path, '/');
        $path = rtrim($path, '/');
        $path = rtrim($path, '?');

        $this->activeWhen = $path;

        return $this;
    }

    public function getActiveWhen(): string
    {
        return $this->activeWhen;
    }

    public function isNewTab(bool $newTab = true): Item
    {
        $this->newTab = $newTab;

        return $this;
    }

    public function getNewTab(): bool
    {
        return $this->newTab;
    }

    public function getItemClass(): string
    {
        return $this->itemClass;
    }

    public function setItemClass(string $class): self
    {
        $this->itemClass = $class;

        return $this;
    }
}
