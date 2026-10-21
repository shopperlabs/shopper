<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderItem;
use Shopper\Core\Models\Product;
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

    it('renders the top selling products again on a cache that refuses to unserialize models', function (): void {
        config(['cache.default' => 'array', 'cache.stores.array.serialize' => true, 'cache.serializable_classes' => false]);
        Cache::forgetDriver('array');

        $product = Product::factory()->create(['name' => 'Best Seller']);

        OrderItem::factory()->create([
            'product_id' => $product->id,
            'order_id' => Order::factory()->create(['payment_status' => PaymentStatus::Paid, 'created_at' => now()])->id,
        ]);

        foreach ([1, 2] as $render) {
            Livewire::test(TopSellingProducts::class)->assertOk()->assertSee('Best Seller');
        }
    });
});
