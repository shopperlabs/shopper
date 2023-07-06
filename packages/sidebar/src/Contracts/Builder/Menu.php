<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Contracts\Builder;

use Closure;
use Illuminate\Support\Collection;

interface Menu extends Authorizable
{
    public function group(string $name, Closure $callback = null): Group;

    public function addGroup(Group $group): self;

    public function getGroups(): Collection;

    public function add(Menu $menu): self;
}
