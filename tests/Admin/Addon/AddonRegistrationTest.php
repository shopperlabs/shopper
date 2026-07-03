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
