<?php

declare(strict_types=1);

use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Payment\Enum\TransactionStatus;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Facades\Payment;
use Shopper\Payment\Models\PaymentTransaction;
use Shopper\Payment\Models\PaymentWebhookEvent;
use Tests\Api\Stubs\FakePaymentDriver;

uses(Tests\Api\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    Payment::extend('fake', fn (): FakePaymentDriver => new FakePaymentDriver);

    $this->method = PaymentMethod::factory()->create(['driver' => 'fake', 'is_enabled' => true]);
    $this->order = Order::factory()->create([
        'payment_method_id' => $this->method->id,
        'price_amount' => 5000,
        'currency_code' => 'USD',
        'payment_status' => PaymentStatus::Pending,
    ]);

    PaymentTransaction::query()->create([
        'order_id' => $this->order->id,
        'payment_method_id' => $this->method->id,
        'driver' => 'fake',
        'type' => TransactionType::Initiate,
        'status' => TransactionStatus::Pending,
        'amount' => 5000,
        'currency_code' => 'USD',
        'reference' => 'pi_abc',
    ]);
});

it('returns 404 for an unknown driver', function (): void {
    $this->postJson('/store/webhooks/unknown', ['action' => 'captured'])
        ->assertNotFound();
});

it('returns 404 for a registered driver that is not configured', function (): void {
    Payment::extend('unconfigured', fn (): FakePaymentDriver => new FakePaymentDriver(configured: false));

    $this->postJson('/store/webhooks/unconfigured', ['action' => 'captured'])
        ->assertNotFound();
});

it('rejects an unverified payload with a 400', function (): void {
    $this->postJson('/store/webhooks/fake', ['action' => 'invalid'])
        ->assertStatus(400);

    expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Pending);
});

it('advances the order to Paid on a captured event', function (): void {
    $this->postJson('/store/webhooks/fake', [
        'action' => 'captured',
        'reference' => 'pi_abc',
        'amount' => 5000,
        'event_id' => 'evt_capture_1',
    ])->assertOk()->assertJson(['received' => true]);

    expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Paid)
        ->and(PaymentTransaction::query()->where('order_id', $this->order->id)->where('type', TransactionType::Capture)->count())->toBe(1)
        ->and(PaymentWebhookEvent::query()->where('event_id', 'evt_capture_1')->firstOrFail()->isProcessed())->toBeTrue();
});

it('is idempotent: a redelivered event id is not processed twice', function (): void {
    $payload = [
        'action' => 'captured',
        'reference' => 'pi_abc',
        'amount' => 5000,
        'event_id' => 'evt_capture_2',
    ];

    $this->postJson('/store/webhooks/fake', $payload)->assertOk();
    $this->postJson('/store/webhooks/fake', $payload)->assertOk();

    expect(PaymentTransaction::query()->where('order_id', $this->order->id)->where('type', TransactionType::Capture)->count())->toBe(1)
        ->and(PaymentWebhookEvent::query()->where('event_id', 'evt_capture_2')->count())->toBe(1);
});
