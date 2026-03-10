<?php

declare(strict_types=1);

use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Channel;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderAddress;
use Shopper\Core\Models\OrderItem;
use Shopper\Core\Models\OrderRefund;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\Zone;
use Tests\Core\Stubs\User;

uses(Tests\Core\TestCase::class);

describe(Order::class, function (): void {
    it('calculates total from order items', function (): void {
        /** @var Order $order */
        $order = Order::factory()->create();
        OrderItem::factory()->count(3)->create([
            'order_id' => $order->id,
            'unit_price_amount' => 1000,
            'quantity' => 2,
        ]);

        expect($order->total())->toBe(6000);
    });

    it('checks order lifecycle status methods', function (): void {
        $new = Order::factory()->create(['status' => OrderStatus::New]);
        $processing = Order::factory()->create(['status' => OrderStatus::Processing]);
        $completed = Order::factory()->create(['status' => OrderStatus::Completed]);
        $cancelled = Order::factory()->create(['status' => OrderStatus::Cancelled]);
        $archived = Order::factory()->create(['status' => OrderStatus::Archived]);

        expect($new->isNew())->toBeTrue()
            ->and($processing->isProcessing())->toBeTrue()
            ->and($completed->isCompleted())->toBeTrue()
            ->and($cancelled->isNotCancelled())->toBeFalse()
            ->and($completed->isNotCancelled())->toBeTrue()
            ->and($archived->isArchived())->toBeTrue();
    });

    it('checks payment status methods', function (): void {
        $pending = Order::factory()->create(['payment_status' => PaymentStatus::Pending]);
        $authorized = Order::factory()->create(['payment_status' => PaymentStatus::Authorized]);
        $paid = Order::factory()->create(['payment_status' => PaymentStatus::Paid]);
        $refunded = Order::factory()->create(['payment_status' => PaymentStatus::Refunded]);

        expect($pending->isPaymentPending())->toBeTrue()
            ->and($authorized->isPaymentAuthorized())->toBeTrue()
            ->and($paid->isPaid())->toBeTrue()
            ->and($refunded->isRefunded())->toBeTrue();
    });

    it('checks shipping status methods', function (): void {
        $pending = Order::factory()->create(['shipping_status' => ShippingStatus::Unfulfilled]);
        $shipped = Order::factory()->create(['shipping_status' => ShippingStatus::Shipped]);

        expect($pending->isShippingPending())->toBeTrue()
            ->and($shipped->isShipped())->toBeTrue();
    });

    it('checks `canBeCancelled()` requires pending shipping and non-terminal status', function (): void {
        $canCancel = Order::factory()->create([
            'status' => OrderStatus::Processing,
            'shipping_status' => ShippingStatus::Unfulfilled,
        ]);
        $cannotCancelShipped = Order::factory()->create([
            'status' => OrderStatus::Processing,
            'shipping_status' => ShippingStatus::Shipped,
        ]);
        $alreadyCancelled = Order::factory()->create([
            'status' => OrderStatus::Cancelled,
            'shipping_status' => ShippingStatus::Unfulfilled,
        ]);
        $archived = Order::factory()->create([
            'status' => OrderStatus::Archived,
            'shipping_status' => ShippingStatus::Unfulfilled,
        ]);

        expect($canCancel->canBeCancelled())->toBeTrue()
            ->and($cannotCancelShipped->canBeCancelled())->toBeFalse()
            ->and($alreadyCancelled->canBeCancelled())->toBeFalse()
            ->and($archived->canBeCancelled())->toBeFalse();
    });

    it('has customer relationship', function (): void {
        $customer = User::factory()->create();
        $order = Order::factory()->create(['customer_id' => $customer->id]);

        expect($order->customer)->toBeInstanceOf(User::class)
            ->and($order->customer->id)->toBe($customer->id);
    });

    it('has order items relationship', function (): void {
        $order = Order::factory()->create();
        OrderItem::factory()->count(5)->create(['order_id' => $order->id]);

        expect($order->items()->count())->toBe(5);
    });

    it('has shipping address relationship', function (): void {
        $address = OrderAddress::factory()->create();
        $order = Order::factory()->create(['shipping_address_id' => $address->id]);

        expect($order->shippingAddress)->toBeInstanceOf(OrderAddress::class)
            ->and($order->shippingAddress->id)->toBe($address->id);
    });

    it('has billing address relationship', function (): void {
        $address = OrderAddress::factory()->create();
        $order = Order::factory()->create(['billing_address_id' => $address->id]);

        expect($order->billingAddress)->toBeInstanceOf(OrderAddress::class)
            ->and($order->billingAddress->id)->toBe($address->id);
    });

    it('has payment method relationship', function (): void {
        $paymentMethod = PaymentMethod::factory()->create();
        $order = Order::factory()->create(['payment_method_id' => $paymentMethod->id]);

        expect($order->paymentMethod)->toBeInstanceOf(PaymentMethod::class)
            ->and($order->paymentMethod->id)->toBe($paymentMethod->id);
    });

    it('has zone relationship', function (): void {
        $zone = Zone::factory()->create();
        $order = Order::factory()->create(['zone_id' => $zone->id]);

        expect($order->zone)->toBeInstanceOf(Zone::class)
            ->and($order->zone->id)->toBe($zone->id);
    });

    it('has channel relationship', function (): void {
        $channel = Channel::factory()->create();
        $order = Order::factory()->create(['channel_id' => $channel->id]);

        expect($order->channel)->toBeInstanceOf(Channel::class)
            ->and($order->channel->id)->toBe($channel->id);
    });

    it('has parent order relationship', function (): void {
        $parent = Order::factory()->create();
        $child = Order::factory()->create(['parent_order_id' => $parent->id]);

        expect($child->parent)->toBeInstanceOf(Order::class)
            ->and($child->parent->id)->toBe($parent->id);
    });

    it('has children orders relationship', function (): void {
        $parent = Order::factory()->create();
        Order::factory()->count(3)->create(['parent_order_id' => $parent->id]);

        expect($parent->children()->count())->toBe(3);
    });

    it('has refund relationship', function (): void {
        $order = Order::factory()->create();
        OrderRefund::factory()->create(['order_id' => $order->id]);

        expect($order->refund)->toBeInstanceOf(OrderRefund::class);
    });

    it('has shipping option relationship', function (): void {
        $carrierOption = CarrierOption::factory()->create();
        /** @var Order $order */
        $order = Order::factory()->create(['shipping_option_id' => $carrierOption->id]);

        expect($order->shippingOption)->toBeInstanceOf(CarrierOption::class)
            ->and($order->shippingOption->id)->toBe($carrierOption->id);
    });

    it('sets default statuses on creation', function (): void {
        $order = new Order;

        expect($order->status)->toBe(OrderStatus::New)
            ->and($order->payment_status)->toBe(PaymentStatus::Pending)
            ->and($order->shipping_status)->toBe(ShippingStatus::Unfulfilled);
    });
})->group('order', 'models');
