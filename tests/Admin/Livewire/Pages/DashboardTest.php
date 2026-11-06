<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Setting;
use Shopper\Livewire\Components\Dashboard\RecentOrders;
use Shopper\Livewire\Components\Dashboard\RevenueChart;
use Shopper\Livewire\Components\Dashboard\StatCards;
use Shopper\Livewire\Components\Dashboard\TopSellingProducts;
use Shopper\Livewire\Pages\Dashboard;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('browse_orders');
    $this->actingAs($this->user);
});

describe(Dashboard::class, function (): void {
    it('can render dashboard component', function (): void {
        Livewire::test(Dashboard::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.dashboard');
    });

    it('has correct page title', function (): void {
        Livewire::test(Dashboard::class)
            ->assertOk()
            ->assertSet('showSetupGuide', true);
    });

    it('requires browse_orders to render the order widgets', function (string $component): void {
        $this->user->revokePermissionTo('browse_orders');

        Livewire::test($component)->assertForbidden();
    })->with([
        RevenueChart::class,
        TopSellingProducts::class,
        RecentOrders::class,
    ]);

    it('renders the dashboard without the order widgets for users who cannot browse orders', function (): void {
        $this->user->revokePermissionTo('browse_orders');

        Setting::query()->create([
            'key' => 'setup_guide_done',
            'value' => true,
            'display_name' => 'Setup guide done',
            'locked' => true,
        ]);

        Livewire::test(Dashboard::class)->assertOk();
    });

    it('only shows the stat cards of the resources the user can browse', function (): void {
        $this->user->syncPermissions('browse_products');

        $cards = Livewire::test(StatCards::class)->instance()->cards;

        expect($cards)->toHaveCount(1)
            ->and($cards[0]['label'])->toBe(__('shopper::pages/dashboard.stats.products'));
    });
});
