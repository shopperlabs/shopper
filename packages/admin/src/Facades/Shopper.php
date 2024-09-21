<?php

declare(strict_types=1);

namespace Shopper\Facades;

use Closure;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Facade;
use Shopper\ShopperPanel;

/**
 * @method static StatefulGuard auth()
 * @method static string prefix()
 * @method static Htmlable getThemeLink()
 * @method static void registerScripts(array $scripts)
 * @method static void registerStyles(array $styles)
 * @method static void registerTheme(string | Htmlable | null $theme)
 * @method static void registerViteTheme(string | array $theme, string | null $buildDirectory = null)
 * @method static void serving(Closure $callback)
 * @method static void setServingStatus(bool $condition = true)
 * @method static string version()
 *
 * @see ShopperPanel
 */
final class Shopper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'shopper';
    }
}
