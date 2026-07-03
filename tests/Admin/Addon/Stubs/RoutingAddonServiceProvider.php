<?php

declare(strict_types=1);

namespace Tests\Admin\Addon\Stubs;

use Illuminate\Support\ServiceProvider;
use Shopper\Facades\Shopper;

final class RoutingAddonServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Shopper::addons([new RoutingAddon]);
    }
}
