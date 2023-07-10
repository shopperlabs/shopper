<?php

declare(strict_types=1);

namespace Shopper\Sidebar;

use Illuminate\Contracts\Container\Container;
use Shopper\Sidebar\Exceptions\LogicException;
use Shopper\Sidebar\Infrastructure\SidebarFlusher;
use Shopper\Sidebar\Infrastructure\SidebarResolver;

final class SidebarManager
{
    protected array $sidebars = [];

    public function __construct(
        protected Container $container,
        protected SidebarResolver $resolver
    ) {
    }

    public function register(string $name): self
    {
        if (class_exists($name)) {
            $this->sidebars[] = $name;
        } else {
            throw new LogicException('Sidebar [' . $name . '] does not exist');
        }

        return $this;
    }

    public function resolve(): void
    {
        foreach ($this->sidebars as $name) {
            $sidebar = $this->resolver->resolve($name);
            $this->container->singleton($name, fn () => $sidebar);
        }
    }

    public function flush(SidebarFlusher $flusher): void
    {
        foreach ($this->sidebars as $name) {
            $flusher->flush($name);
        }
    }
}
