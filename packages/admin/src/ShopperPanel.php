<?php

declare(strict_types=1);

namespace Shopper;

use Closure;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Shopper\Addon\AddonManager;
use Shopper\Contracts\ShopperAddon;
use Shopper\Enum\RenderHook;
use Shopper\Events\LoadShopper;

final class ShopperPanel
{
    private bool $isServing = false;

    private string|Htmlable|null $theme = null;

    /** @var array{path: string|array<string, mixed>, buildDirectory: string|null}|null */
    private ?array $viteTheme = null;

    /** @var string|Closure(): (Htmlable|string)|null */
    private string|Closure|null $brandLogo = null;

    /** @var array<string, list<Closure(): string>> */
    private array $renderHooks = [];

    /** @var list<string> */
    private array $styles = [];

    /** @var list<string> */
    private array $scripts = [];

    private ?AddonManager $addonManager = null;

    public function auth(): StatefulGuard
    {
        /** @var StatefulGuard */
        return auth()->guard(config('shopper.auth.guard'));
    }

    public function prefix(): string
    {
        return config('shopper.admin.prefix');
    }

    public function addonManager(): AddonManager
    {
        if ($this->addonManager === null) {
            $this->addonManager = new AddonManager;
        }

        return $this->addonManager;
    }

    public function addon(ShopperAddon $addon): self
    {
        $this->addonManager()->register($addon, $this);

        return $this;
    }

    public function hasAddon(string $id): bool
    {
        return $this->addonManager()->has($id);
    }

    public function getAddon(string $id): ShopperAddon
    {
        return $this->addonManager()->get($id);
    }

    public function addonRoutes(Closure $routes): self
    {
        $this->addonManager()->addRoutes($routes);

        return $this;
    }

    /**
     * @param  class-string  $sidebarClass
     */
    public function addonSidebar(string $sidebarClass): self
    {
        $this->addonManager()->addSidebar($sidebarClass);

        return $this;
    }

    /**
     * @param  array<string, class-string>  $components
     */
    public function addonLivewireComponents(array $components): self
    {
        $this->addonManager()->addLivewireComponents($components);

        return $this;
    }

    public function addonViews(string $namespace, string $path): self
    {
        $this->addonManager()->addViewNamespace($namespace, $path);

        return $this;
    }

    /**
     * @param  array<class-string, bool>  $items
     */
    public function addonSettingItems(array $items): self
    {
        $this->addonManager()->addSettingItems($items);

        return $this;
    }

    /**
     * @param  list<string>  $permissions
     */
    public function addonPermissions(array $permissions): self
    {
        $this->addonManager()->addPermissions($permissions);

        return $this;
    }

    /**
     * @param  list<string>  $styles
     */
    public function addonStyles(array $styles): self
    {
        $this->addonManager()->addStyles($styles);

        return $this;
    }

    /**
     * @param  list<string>  $scripts
     */
    public function addonScripts(array $scripts): self
    {
        $this->addonManager()->addScripts($scripts);

        return $this;
    }

    public function registerTheme(string|Htmlable|null $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @param  string|array<string, mixed>  $theme
     */
    public function registerViteTheme(string|array $theme, ?string $buildDirectory = null): self
    {
        $this->viteTheme = ['path' => $theme, 'buildDirectory' => $buildDirectory];

        return $this;
    }

    public function getThemeLink(): Htmlable
    {
        if ($this->viteTheme !== null) {
            return app(Vite::class)($this->viteTheme['path'], $this->viteTheme['buildDirectory']);
        }

        if (Str::of($this->theme)->contains('<link')) {
            return $this->theme instanceof Htmlable ? $this->theme : new HtmlString($this->theme);
        }

        $url = $this->theme ?? route('shopper.asset', [
            'id' => get_asset_id('shopper.css'),
            'file' => 'shopper.css',
        ]);

        return new HtmlString("<link rel=\"stylesheet\" href=\"{$url}\" />");
    }

    /**
     * @param  string|Closure(): (Htmlable|string)  $brandLogo
     */
    public function brandLogo(string|Closure $brandLogo): self
    {
        $this->brandLogo = $brandLogo;

        return $this;
    }

    public function getBrandLogo(): ?Htmlable
    {
        if ($this->brandLogo instanceof Closure) {
            $result = ($this->brandLogo)();

            if (is_string($result)) {
                return new HtmlString($result);
            }

            return $result;
        }

        if (is_string($this->brandLogo)) {
            return new HtmlString($this->brandLogo);
        }

        return null;
    }

    public function renderHook(RenderHook $hook, Closure $callback): self
    {
        $this->renderHooks[$hook->value][] = $callback;

        return $this;
    }

    public function getRenderHook(RenderHook $hook): Htmlable
    {
        $output = collect($this->renderHooks[$hook->value] ?? [])
            ->map(fn (Closure $callback): string => $callback())
            ->implode('');

        return new HtmlString($output);
    }

    /**
     * @param  list<string>  $styles
     */
    public function styles(array $styles): self
    {
        $this->styles = array_merge($this->styles, $styles);

        return $this;
    }

    /**
     * @return list<string>
     */
    public function getStyles(): array
    {
        return array_merge(
            config('shopper.admin.resources.stylesheets', []),
            $this->styles,
            $this->addonManager()->getStyles(),
        );
    }

    /**
     * @param  list<string>  $scripts
     */
    public function scripts(array $scripts): self
    {
        $this->scripts = array_merge($this->scripts, $scripts);

        return $this;
    }

    /**
     * @return list<string>
     */
    public function getScripts(): array
    {
        return array_merge(
            config('shopper.admin.resources.scripts', []),
            $this->scripts,
            $this->addonManager()->getScripts(),
        );
    }

    public function setServingStatus(bool $condition = true): void
    {
        $this->isServing = $condition;
    }

    public function isServing(): bool
    {
        return $this->isServing;
    }

    public function serving(Closure $callback): void
    {
        Event::listen(LoadShopper::class, $callback);
    }

    public function version(): string
    {
        return 'v2.6';
    }
}
