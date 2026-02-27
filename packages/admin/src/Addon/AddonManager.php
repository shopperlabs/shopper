<?php

declare(strict_types=1);

namespace Shopper\Addon;

use Closure;
use Illuminate\Support\Collection;
use LogicException;
use Shopper\Contracts\ShopperAddon;
use Shopper\ShopperPanel;

final class AddonManager
{
    /** @var array<string, ShopperAddon> */
    private array $addons = [];

    /** @var list<Closure> */
    private array $routes = [];

    /** @var list<class-string> */
    private array $sidebars = [];

    /** @var array<string, class-string> */
    private array $livewireComponents = [];

    /** @var array<string, string> */
    private array $viewNamespaces = [];

    /** @var array<class-string, bool> */
    private array $settingItems = [];

    /** @var list<string> */
    private array $permissions = [];

    /** @var list<string> */
    private array $styles = [];

    /** @var list<string> */
    private array $scripts = [];

    public function register(ShopperAddon $addon, ShopperPanel $panel): void
    {
        if (isset($this->addons[$addon->getId()])) {
            throw new LogicException("Addon [{$addon->getId()}] is already registered.");
        }

        if (! $addon->isEnabled()) {
            return;
        }

        $this->addons[$addon->getId()] = $addon;
        $addon->register($panel);
    }

    public function boot(ShopperPanel $panel): void
    {
        foreach ($this->addons as $addon) {
            $addon->boot($panel);
        }
    }

    public function addRoutes(Closure $routes): void
    {
        $this->routes[] = $routes;
    }

    /**
     * @return list<Closure>
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @param  class-string  $sidebarClass
     */
    public function addSidebar(string $sidebarClass): void
    {
        $this->sidebars[] = $sidebarClass;
    }

    /**
     * @return list<class-string>
     */
    public function getSidebars(): array
    {
        return $this->sidebars;
    }

    /**
     * @param  array<string, class-string>  $components
     */
    public function addLivewireComponents(array $components): void
    {
        $this->livewireComponents = array_merge($this->livewireComponents, $components);
    }

    /**
     * @return array<string, class-string>
     */
    public function getLivewireComponents(): array
    {
        return $this->livewireComponents;
    }

    public function addViewNamespace(string $namespace, string $path): void
    {
        $this->viewNamespaces[$namespace] = $path;
    }

    /**
     * @return array<string, string>
     */
    public function getViewNamespaces(): array
    {
        return $this->viewNamespaces;
    }

    /**
     * @param  array<class-string, bool>  $items
     */
    public function addSettingItems(array $items): void
    {
        $this->settingItems = array_merge($this->settingItems, $items);
    }

    /**
     * @return array<class-string, bool>
     */
    public function getSettingItems(): array
    {
        return $this->settingItems;
    }

    /**
     * @param  list<string>  $permissions
     */
    public function addPermissions(array $permissions): void
    {
        $this->permissions = array_merge($this->permissions, $permissions);
    }

    /**
     * @return list<string>
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * @param  list<string>  $styles
     */
    public function addStyles(array $styles): void
    {
        $this->styles = array_merge($this->styles, $styles);
    }

    /**
     * @return list<string>
     */
    public function getStyles(): array
    {
        return $this->styles;
    }

    /**
     * @param  list<string>  $scripts
     */
    public function addScripts(array $scripts): void
    {
        $this->scripts = array_merge($this->scripts, $scripts);
    }

    /**
     * @return list<string>
     */
    public function getScripts(): array
    {
        return $this->scripts;
    }

    public function has(string $id): bool
    {
        return isset($this->addons[$id]);
    }

    public function get(string $id): ShopperAddon
    {
        return $this->addons[$id] ?? throw new LogicException("Addon [{$id}] is not registered.");
    }

    /**
     * @return Collection<string, ShopperAddon>
     */
    public function all(): Collection
    {
        return collect($this->addons);
    }
}
