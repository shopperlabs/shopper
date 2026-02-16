<?php

declare(strict_types=1);

namespace Shopper;

use Closure;
use Exception;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Shopper\Enum\RenderHook;
use Shopper\Events\LoadShopper;

final class ShopperPanel
{
    private bool $isServing = false;

    private string|Htmlable|null $theme = null;

    /** @var string|Closure(): (Htmlable|string)|null */
    private string|Closure|null $brandLogo = null;

    /** @var array<string, list<Closure(): string>> */
    private array $renderHooks = [];

    /** @var list<string> */
    private array $styles = [];

    /** @var list<string> */
    private array $scripts = [];

    public function auth(): StatefulGuard
    {
        /** @var StatefulGuard */
        return auth()->guard(config('shopper.auth.guard'));
    }

    public function prefix(): string
    {
        return config('shopper.admin.prefix');
    }

    public function registerTheme(string|Htmlable|null $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @param  string|array<string, mixed>  $theme
     *
     * @throws Exception
     */
    public function registerViteTheme(string|array $theme, ?string $buildDirectory = null): self
    {
        $this->theme = app(Vite::class)($theme, $buildDirectory);

        return $this;
    }

    public function getThemeLink(): Htmlable
    {
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
        return 'v2';
    }
}
