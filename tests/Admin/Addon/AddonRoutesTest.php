<?php

declare(strict_types=1);

namespace Tests\Admin\Addon;

use PHPUnit\Framework\Attributes\Test;
use Shopper\Http\Middleware\Authenticate;
use Tests\Admin\Addon\Stubs\RoutingAddonServiceProvider;
use Tests\Admin\TestCase;

final class AddonRoutesTest extends TestCase
{
    #[Test]
    public function it_registers_addon_routes_inside_the_panel_group(): void
    {
        $route = app('router')->getRoutes()->getByName('shopper.settings.addon-probe');

        $this->assertNotNull($route);
        $this->assertSame(shopper()->prefix().'/setting/addon-probe', $route->uri());
        $this->assertContains(Authenticate::class, $route->gatherMiddleware());
    }

    protected function getPackageProviders($app): array
    {
        return array_merge(parent::getPackageProviders($app), [
            RoutingAddonServiceProvider::class,
        ]);
    }
}
