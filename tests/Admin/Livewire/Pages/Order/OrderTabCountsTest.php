<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Models\Order;
use Shopper\Livewire\Pages\Order\Index;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->assignRole(config('shopper.admin.roles.admin'));
    $this->actingAs($this->user);
});

it('aggregates every tab badge from a single cached query', function (): void {
    Order::factory()->create([
        'status' => OrderStatus::New,
        'payment_status' => PaymentStatus::Pending,
        'shipping_status' => ShippingStatus::Unfulfilled,
    ]);
    Order::factory()->create([
        'status' => OrderStatus::Processing,
        'payment_status' => PaymentStatus::Paid,
        'shipping_status' => ShippingStatus::Unfulfilled,
    ]);
    Order::factory()->create([
        'status' => OrderStatus::Completed,
        'payment_status' => PaymentStatus::Paid,
        'shipping_status' => ShippingStatus::Shipped,
    ]);
    Order::factory()->create([
        'status' => OrderStatus::Cancelled,
        'payment_status' => PaymentStatus::Voided,
        'shipping_status' => ShippingStatus::Unfulfilled,
    ]);

    $counts = Livewire::test(Index::class)->instance()->tabCounts();

    expect($counts['all'])->toBe(4)
        ->and($counts['open'])->toBe(2)
        ->and($counts['paid'])->toBe(2)
        ->and($counts['fulfilled'])->toBe(1)
        ->and($counts['cancelled'])->toBe(1)
        ->and($counts['archived'])->toBe(0);
})->group('livewire', 'orders');
