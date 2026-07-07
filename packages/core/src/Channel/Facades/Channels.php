<?php

declare(strict_types=1);

namespace Shopper\Core\Channel\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Shopper\Core\Channel\ChannelManager;
use Shopper\Core\Channel\Contracts\ChannelDriver;

/**
 * @method static ChannelDriver driver(?string $name = null)
 * @method static ChannelManager extend(string $name, \Closure $callback)
 * @method static array<int, string> availableDrivers()
 * @method static Collection<string, ChannelDriver> configuredDrivers()
 * @method static bool isConfigured(string $name)
 *
 * @see ChannelManager
 */
final class Channels extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ChannelManager::class;
    }
}
