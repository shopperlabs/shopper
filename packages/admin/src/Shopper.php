<?php

declare(strict_types=1);

namespace Shopper;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Shopper\Events\LoadShopper;

final class Shopper
{
    protected array $scripts = [];

    protected array $styles = [];

    protected bool $isServing = false;

    protected string|Htmlable|null $theme = null;

    public static function auth(): Guard
    {
        return auth()->guard(config('shopper.auth.guard'));
    }

    public static function prefix(): string
    {
        return config('shopper.admin.prefix');
    }

    public function registerScripts(array $scripts): void
    {
        $this->scripts = array_merge($this->scripts, $scripts);
    }

    public function registerStyles(array $styles): void
    {
        $this->styles = array_merge($this->styles, $styles);
    }

    public function registerTheme(string|Htmlable|null $theme): void
    {
        $this->theme = $theme;
    }

    public function registerViteTheme(string|array $theme, ?string $buildDirectory = null): void
    {
        $this->theme = app(Vite::class)($theme, $buildDirectory);
    }

    public function getScripts(): array
    {
        return $this->scripts;
    }

    public function getStyles(): array
    {
        return $this->styles;
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
}
