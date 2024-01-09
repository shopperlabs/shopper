<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Domain;

use Closure;
use Illuminate\Contracts\Container\Container;
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

class DefaultItem implements Item, Serializable
{
    use AuthorizableTrait;
    use CacheableTrait;
    use CallableTrait;
    use ItemableTrait;
    use RouteableTrait;

    protected Collection $badges;

    protected Collection $appends;

    protected ?string $name = null;

    protected int $weight = 0;

    protected ?string $icon = null;

    protected string $iconClass = '';

    protected string $type = 'blade';

    protected string $toggleIconType = 'blade';

    protected string $toggleIcon = 'untitledui-chevron-down';

    protected string $toggleActiveIcon = 'untitledui-chevron-up';

    protected string $toggleIconClass = 'h-5 w-5';

    protected string $toggleActiveIconClass = 'h-5 w-5';

    protected bool $activeWhen = false;

    protected bool $newTab = false;

    protected string $itemClass = '';

    protected string $activeClass = '';

    protected string $inactiveClass = '';

    protected string $parentItemClass = '';

    protected array $cacheables = [
        'name',
        'weight',
        'url',
        'icon',
        'type',
        'toggleIcon',
        'toggleActiveIcon',
        'items',
        'badges',
        'appends',
        'authorized',
    ];

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

    public function setName(string $name): Item
    {
        $this->name = $name;

        return $this;
    }

    public function weight(int $weight): Item
    {
        $this->weight = $weight;

        return $this;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon, string $type = 'blade', string $iconClass = ''): Item
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

    public function getToggleIcon(): string
    {
        return $this->toggleIcon;
    }

    public function getActiveToggleIcon(): string
    {
        return $this->toggleActiveIcon;
    }

    public function getToggleIconClass(): string
    {
        return $this->toggleIconClass;
    }

    public function getActiveToggleIconClass(): string
    {
        return $this->toggleActiveIconClass;
    }

    public function toggleIcon(string $icon, string $type = 'blade', string $iconClass = ''): self
    {
        $this->toggleIcon = $icon;
        $this->toggleIconType = $type;
        $this->toggleIconClass = $iconClass;

        return $this;
    }

    public function toggleActiveIcon(string $icon, string $type = 'blade', string $iconClass = ''): self
    {
        $this->toggleActiveIcon = $icon;
        $this->toggleIconType = $type;
        $this->toggleActiveIconClass = $iconClass;

        return $this;
    }

    public function iconSvg(): bool
    {
        return $this->type === 'svg';
    }

    public function toggleIconSvg(): bool
    {
        return $this->toggleIconType === 'svg';
    }

    public function badge(mixed $callbackOrValue = null, ?string $className = null): Badge
    {
        $badge = $this->container->make(Badge::class);

        if ($callbackOrValue instanceof Closure) {
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

    public function getBadges(): Collection
    {
        return $this->badges;
    }

    public function append(mixed $callbackOrUrl = null, ?string $icon = null, ?string $name = null): Append
    {
        $append = $this->container->make(Append::class);

        if ($callbackOrUrl instanceof Closure) {
            $this->call($callbackOrUrl, $append);
        } elseif ($callbackOrUrl) {
            $append->route($callbackOrUrl);
        }

        if ($name) {
            $append->setName($name);
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

    public function getAppends(): Collection
    {
        return $this->appends;
    }

    public function isActiveWhen(string $path): Item
    {
        $path = ltrim($path, '/');
        $path = rtrim($path, '/');
        $path = rtrim($path, '?');

        $this->activeWhen = (bool) $path;

        return $this;
    }

    public function getActiveWhen(): bool
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

    public function getActiveClass(): string
    {
        return $this->activeClass;
    }

    public function setActiveClass(string $class): self
    {
        $this->activeClass = $class;

        return $this;
    }

    public function getInactiveClass(): string
    {
        return $this->inactiveClass;
    }

    public function setInactiveClass(string $class): self
    {
        $this->inactiveClass = $class;

        return $this;
    }

    public function getParentItemClass(): string
    {
        return $this->parentItemClass;
    }

    public function setParentItemClass(string $class): self
    {
        $this->parentItemClass = $class;

        return $this;
    }
}
