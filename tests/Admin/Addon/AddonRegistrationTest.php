<?php

declare(strict_types=1);

use Shopper\Addon\BaseAddon;
use Shopper\ShopperPanel;

uses(Tests\Admin\TestCase::class);

final class FakeFirstAddon extends BaseAddon
{
    public function getId(): string
    {
        return 'fake-first';
    }

    public function register(ShopperPanel $panel): void {}
}

final class FakeSecondAddon extends BaseAddon
{
    public function getId(): string
    {
        return 'fake-second';
    }

    public function register(ShopperPanel $panel): void {}
}

it('registers multiple addons through `addons()`', function (): void {
    $panel = new ShopperPanel;

    $panel->addons([
        new FakeFirstAddon,
        new FakeSecondAddon,
    ]);

    expect($panel->hasAddon('fake-first'))->toBeTrue()
        ->and($panel->hasAddon('fake-second'))->toBeTrue();
});

it('throws when the same addon is registered twice', function (): void {
    $panel = new ShopperPanel;

    expect(fn (): ShopperPanel => $panel->addons([
        new FakeFirstAddon,
        new FakeFirstAddon,
    ]))->toThrow(LogicException::class);
});

it('registers the routes an addon declares', function (): void {
    $provider = app()->getProvider(Shopper\ShopperServiceProvider::class);

    $consume = (new ReflectionMethod($provider, 'registerAddonRoutes'))
        ->getClosure($provider);

    $consume([function (): void {
        Illuminate\Support\Facades\Route::get('/addon-probe', fn (): string => 'ok')->name('shopper.addon-probe');
    }]);

    $registered = collect(app('router')->getRoutes()->getRoutes())
        ->contains(fn ($route): bool => $route->getName() === 'shopper.addon-probe'
            && str_starts_with($route->uri(), shopper()->prefix()));

    expect($registered)->toBeTrue();
});
