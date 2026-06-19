<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Livewire\Components\Dashboard\RecentOrders;
use Shopper\Livewire\Components\Dashboard\StatCards;
use Shopper\Livewire\Components\Dashboard\TopSellingProducts;
use Shopper\Livewire\Pages\Dashboard;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
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

    it('renders skeleton placeholders for the lazy widgets', function (string $view): void {
        expect(view($view)->render())->toBeString();
    })->with([
        'shopper::livewire.components.dashboard.placeholders.stat-cards',
        'shopper::livewire.components.dashboard.placeholders.recent-orders',
        'shopper::livewire.components.dashboard.placeholders.top-selling-products',
    ]);

    it('renders the read-only dashboard widgets', function (string $component): void {
        Livewire::test($component)->assertOk();
    })->with([
        StatCards::class,
        TopSellingProducts::class,
        RecentOrders::class,
    ]);
});
