<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Infrastructure;

use Illuminate\Contracts\Container\Container;
use Shopper\Sidebar\Contracts\Sidebar;
use Shopper\Sidebar\Exceptions\LogicException;

final class ContainerResolver implements SidebarResolver
{
    public function __construct(protected Container $container)
    {
    }

    public function resolve(string $name): Sidebar
    {
        $sidebar = $this->container->make($name);

        if (! $sidebar instanceof Sidebar) {
            throw new LogicException('Your sidebar should implement the Sidebar interface');
        }

        $sidebar->build();

        return $sidebar;
    }
}
