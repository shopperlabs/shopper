<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Contracts\Builder;

use Illuminate\Support\Collection;
use Shopper\Sidebar\Contracts\Routeable;

interface Item extends Itemable, Authorizable, Routeable
{
    public function getName(): string;

    public function setName(string $name): self;

    public function weight(int $weight): self;

    public function getWeight(): int;

    public function getIcon(): string;

    public function setIcon(string $icon, string $type = 'blade'): self;

    public function getToggleIcon(): string;

    public function toggleIcon(string $icon): self;

    public function isBladeType(): bool;

    public function getUrl(): string;

    public function setUrl(string $url): self;

    public function route(array|string $route, array $params = []): self;

    public function badge(mixed $callbackOrValue = null, string $className = null): Badge;

    public function addBadge(Badge $badge): Badge;

    public function getBadges(): Collection;

    public function append(mixed $callbackOrUrl = null, string $icon = null, string $name = null): Append;

    public function addAppend(Append $append): Append;

    public function getAppends(): Collection;

    public function isActiveWhen(string $path): self;

    public function getActiveWhen(): bool;

    public function isNewTab(bool $newTab): self;

    public function getNewTab(): bool;

    public function getItemClass(): string;

    public function setItemClass(string $class): self;
}
