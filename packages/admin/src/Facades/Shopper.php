<?php

declare(strict_types=1);

namespace Shopper\Facades;

use Closure;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Facade;
use Shopper\Enum\RenderHook;
use Shopper\ShopperPanel;

/**
 * @method static StatefulGuard auth()
 * @method static string prefix()
 * @method static Htmlable getThemeLink()
 * @method static ShopperPanel registerTheme(string | Htmlable | null $theme)
 * @method static ShopperPanel registerViteTheme(string | array $theme, string | null $buildDirectory = null)
 * @method static ShopperPanel brandLogo(string | Closure $brandLogo)
 * @method static Htmlable|null getBrandLogo()
 * @method static ShopperPanel renderHook(RenderHook $hook, Closure $callback)
 * @method static Htmlable getRenderHook(RenderHook $hook)
 * @method static ShopperPanel styles(array $styles)
 * @method static ShopperPanel scripts(array $scripts)
 * @method static array getStyles()
 * @method static array getScripts()
 * @method static void serving(Closure $callback)
 * @method static void setServingStatus(bool $condition = true)
 * @method static bool isServing()
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
