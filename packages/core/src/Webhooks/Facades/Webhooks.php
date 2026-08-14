<?php

declare(strict_types=1);

namespace Shopper\Core\Webhooks\Facades;

use Illuminate\Support\Facades\Facade;
use Shopper\Core\Webhooks\WebhookRegistry;

/**
 * @method static WebhookRegistry register(string $eventClass, string $name, \Closure|string|null $serializer = null)
 * @method static array<class-string, string> events()
 * @method static ?string nameFor(string $eventClass)
 *
 * @see WebhookRegistry
 */
final class Webhooks extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return WebhookRegistry::class;
    }
}
