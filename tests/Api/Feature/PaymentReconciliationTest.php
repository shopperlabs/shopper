<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Exceptions;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Events\Orders\OrderPaid;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\Zone;
use Shopper\Payment\Actions\SettlePayment;
use Shopper\Payment\DataTransferObjects\WebhookResult;
use Shopper\Payment\Enum\TransactionStatus;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Enum\WebhookAction;
use Shopper\Payment\Facades\Payment;
use Shopper\Payment\Models\PaymentTransaction;
use Shopper\Payment\Models\PaymentWebhookEvent;
use Tests\Api\Stubs\FakePaymentDriver;

uses(Tests\Api\TestCase::class);

/**
 * @param  array<string, mixed>  $data
 */
function earlyEvent(WebhookAction $action, string $reference, string $eventId, int $amount = 3200, array $data = []): PaymentWebhookEvent
{
    return PaymentWebhookEvent::factory()
        ->fromResult(new WebhookResult(action: $action, reference: $reference, amount: $amount, data: $data, eventId: $eventId))
        ->create(['driver' => 'fake']);
}

function transactionsOf(Order $order, TransactionType $type, TransactionStatus $status): int
{
    return PaymentTransaction::query()
        ->where('order_id', $order->id)
        ->where('type', $type)
        ->where('status', $status)
        ->count();
}

beforeEach(function (): void {
    setupCurrencies();

    $driver = $this->driver = new FakePaymentDriver;
    Payment::extend('fake', fn (): FakePaymentDriver => $driver);

    $country = Country::factory()->create(['cca2' => 'US']);
    $zone = Zone::factory()->create(['is_enabled' => true]);
    $zone->countries()->attach($country);

    $carrier = Carrier::factory()->create(['name' => 'Main Carrier', 'slug' => 'main-carrier', 'is_enabled' => true]);
    $zone->carriers()->attach($carrier);

    $option = CarrierOption::factory()->create([
        'name' => 'Standard',
        'price' => 700,
        'is_enabled' => true,
        'carrier_id' => $carrier->id,
        'zone_id' => $zone->id,
    ]);

    $method = PaymentMethod::factory()->create(['title' => 'Card', 'is_enabled' => true, 'driver' => 'fake']);
    $method->zones()->attach($zone);

    $this->inventory = Inventory::factory()->create(['is_default' => true, 'priority' => 0]);
    $this->product = Product::factory()->standard()->publish()->create();
    $this->product->mutateStock($this->inventory->id, 100);

    $this->cart = Cart::factory()->create([
        'currency_code' => 'USD',
        'email' => 'john@example.com',
        'zone_id' => $zone->id,
    ]);

    $this->cart->lines()->create([
        'purchasable_type' => $this->product->getMorphClass(),
        'purchasable_id' => $this->product->id,
        'quantity' => 1,
        'unit_price_amount' => 2500,
    ]);

    $this->cart->addresses()->create([
        'type' => AddressType::Shipping,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'address_1' => '1 Main Street',
        'city' => 'New York',
        'postal_code' => '10001',
    ]);

    $this->cart->update([
        'shipping_option_id' => "main-carrier:{$option->public_id}",
        'shipping_amount' => $option->price,
        'payment_method_id' => $method->id,
    ]);

    $this->completeUrl = "/store/carts/{$this->cart->public_id}/complete";

    $this->reference = $this->postJson("/store/carts/{$this->cart->public_id}/payment-session")
        ->assertCreated()
        ->json('data.id');
});

it('settles a captured event that arrived before the cart was completed', function (): void {
    $event = earlyEvent(WebhookAction::Captured, $this->reference, 'evt_early_capture');

    $orderId = $this->postJson($this->completeUrl)
        ->assertCreated()
        ->assertJsonPath('data.attributes.payment_status', PaymentStatus::Paid->value)
        ->json('data.id');

    $order = Order::query()->where('public_id', $orderId)->firstOrFail();

    expect($order->payment_status)->toBe(PaymentStatus::Paid)
        ->and(transactionsOf($order, TransactionType::Initiate, TransactionStatus::Pending))->toBe(1)
        ->and(transactionsOf($order, TransactionType::Capture, TransactionStatus::Success))->toBe(1)
        ->and($event->refresh()->isProcessed())->toBeTrue()
        ->and($this->driver->retrievals)->toBe(0);
});

it('records an early failed attempt without cancelling the order', function (): void {
    earlyEvent(WebhookAction::Failed, $this->reference, 'evt_early_failed', data: ['failure_message' => 'card_declined']);

    $orderId = $this->postJson($this->completeUrl)->assertCreated()->json('data.id');

    $order = Order::query()->where('public_id', $orderId)->firstOrFail();

    expect($order->status)->toBe(OrderStatus::New)
        ->and($order->payment_status)->toBe(PaymentStatus::Pending)
        ->and(transactionsOf($order, TransactionType::Capture, TransactionStatus::Failed))->toBe(1)
        ->and($this->product->getStock())->toBe(99)
        ->and($this->driver->retrievals)->toBe(1);
});

it('ignores a failed attempt superseded by a later capture', function (): void {
    $declined = earlyEvent(WebhookAction::Failed, $this->reference, 'evt_declined', data: ['failure_message' => 'card_declined']);
    $recovered = earlyEvent(WebhookAction::Captured, $this->reference, 'evt_recovered');

    $orderId = $this->postJson($this->completeUrl)->assertCreated()->json('data.id');

    $order = Order::query()->where('public_id', $orderId)->firstOrFail();

    expect($order->status)->toBe(OrderStatus::New)
        ->and($order->payment_status)->toBe(PaymentStatus::Paid)
        ->and(transactionsOf($order, TransactionType::Capture, TransactionStatus::Success))->toBe(1)
        ->and(transactionsOf($order, TransactionType::Capture, TransactionStatus::Failed))->toBe(0)
        ->and($declined->refresh()->isProcessed())->toBeTrue()
        ->and($recovered->refresh()->isProcessed())->toBeTrue()
        ->and($this->product->getStock())->toBe(99);
});

it('voids the payment and cancels the order on an early canceled event', function (): void {
    earlyEvent(WebhookAction::Canceled, $this->reference, 'evt_early_canceled');

    $orderId = $this->postJson($this->completeUrl)->assertCreated()->json('data.id');

    $order = Order::query()->where('public_id', $orderId)->firstOrFail();

    expect($order->status)->toBe(OrderStatus::Cancelled)
        ->and($order->payment_status)->toBe(PaymentStatus::Voided)
        ->and(transactionsOf($order, TransactionType::Cancel, TransactionStatus::Success))->toBe(1);
});

it('pulls the provider state when no early event settled the payment', function (): void {
    $this->driver->retrievedStatus = 'captured';

    $orderId = $this->postJson($this->completeUrl)
        ->assertCreated()
        ->assertJsonPath('data.attributes.payment_status', PaymentStatus::Paid->value)
        ->json('data.id');

    $order = Order::query()->where('public_id', $orderId)->firstOrFail();

    expect(transactionsOf($order, TransactionType::Capture, TransactionStatus::Success))->toBe(1)
        ->and($this->driver->retrievals)->toBe(1);
});

it('does not pull the provider when the setting is off', function (): void {
    config()->set('shopper.payment.reconciliation.pull_on_completion', false);
    $this->driver->retrievedStatus = 'captured';

    $this->postJson($this->completeUrl)
        ->assertCreated()
        ->assertJsonPath('data.attributes.payment_status', PaymentStatus::Pending->value);

    expect($this->driver->retrievals)->toBe(0);
});

it('leaves the payment pending when the provider reports a non terminal state', function (): void {
    $this->driver->retrievedStatus = 'processing';

    $orderId = $this->postJson($this->completeUrl)
        ->assertCreated()
        ->assertJsonPath('data.attributes.payment_status', PaymentStatus::Pending->value)
        ->json('data.id');

    $order = Order::query()->where('public_id', $orderId)->firstOrFail();

    expect(transactionsOf($order, TransactionType::Capture, TransactionStatus::Success))->toBe(0)
        ->and($this->driver->retrievals)->toBe(1);
});

it('never fails the completion when the provider is unreachable', function (): void {
    Exceptions::fake();
    $this->driver->throwOnRetrieve = true;

    $this->postJson($this->completeUrl)
        ->assertCreated()
        ->assertJsonPath('data.attributes.payment_status', PaymentStatus::Pending->value);

    Exceptions::assertReported(RuntimeException::class);
});

it('keeps an event applied when a listener fails after the commit', function (): void {
    Exceptions::fake();
    Event::listen(OrderPaid::class, function (): void {
        throw new RuntimeException('Listener exploded.');
    });

    $event = earlyEvent(WebhookAction::Captured, $this->reference, 'evt_exploding');

    $orderId = $this->postJson($this->completeUrl)->assertCreated()->json('data.id');

    Exceptions::assertReported(RuntimeException::class);

    $order = Order::query()->where('public_id', $orderId)->firstOrFail();

    expect($order->payment_status)->toBe(PaymentStatus::Paid)
        ->and(transactionsOf($order, TransactionType::Capture, TransactionStatus::Success))->toBe(1)
        ->and($event->refresh()->isProcessed())->toBeTrue();
});

it('hands the event back when its application rolls back', function (): void {
    config()->set('shopper.payment.reconciliation.pull_on_completion', false);

    $orderId = $this->postJson($this->completeUrl)->assertCreated()->json('data.id');
    $order = Order::query()->where('public_id', $orderId)->firstOrFail();
    $order->update(['payment_method_id' => null]);

    $event = earlyEvent(WebhookAction::Captured, $this->reference, 'evt_rolled_back');

    expect(fn () => resolve(SettlePayment::class)->execute($this->reference))->toThrow(TypeError::class)
        ->and($event->refresh()->isProcessed())->toBeFalse()
        ->and($order->refresh()->payment_status)->toBe(PaymentStatus::Pending)
        ->and(transactionsOf($order, TransactionType::Capture, TransactionStatus::Success))->toBe(0);
});

it('records a single capture when the webhook redelivers what the pull already applied', function (): void {
    $this->driver->retrievedStatus = 'captured';

    $orderId = $this->postJson($this->completeUrl)->assertCreated()->json('data.id');

    $this->postJson('/store/webhooks/fake', [
        'action' => 'captured',
        'reference' => $this->reference,
        'amount' => 3200,
        'event_id' => 'evt_late_capture',
    ])->assertOk();

    $order = Order::query()->where('public_id', $orderId)->firstOrFail();

    expect($order->payment_status)->toBe(PaymentStatus::Paid)
        ->and(transactionsOf($order, TransactionType::Capture, TransactionStatus::Success))->toBe(1)
        ->and(PaymentWebhookEvent::query()->where('event_id', 'evt_late_capture')->firstOrFail()->isProcessed())->toBeTrue();
});

it('keeps an early event unprocessed until its order exists', function (): void {
    $this->postJson('/store/webhooks/fake', [
        'action' => 'captured',
        'reference' => $this->reference,
        'amount' => 3200,
        'event_id' => 'evt_orphan',
    ])->assertOk()->assertJson(['received' => true]);

    $event = PaymentWebhookEvent::query()->where('event_id', 'evt_orphan')->firstOrFail();

    expect($event->isProcessed())->toBeFalse()
        ->and($event->reference)->toBe($this->reference);
});

it('settles pending payments from the ledger through the reconcile command', function (): void {
    config()->set('shopper.payment.reconciliation.pull_on_completion', false);

    $orderId = $this->postJson($this->completeUrl)->assertCreated()->json('data.id');

    $slipped = earlyEvent(WebhookAction::Captured, $this->reference, 'evt_slipped');
    $nobody = earlyEvent(WebhookAction::Captured, 'pi_nobody', 'evt_nobody');

    $this->artisan('shopper:payments:reconcile')
        ->expectsOutputToContain('1 payment settled')
        ->expectsOutputToContain('1 event still without an order')
        ->assertSuccessful();

    $order = Order::query()->where('public_id', $orderId)->firstOrFail();

    expect($order->payment_status)->toBe(PaymentStatus::Paid)
        ->and($slipped->refresh()->isProcessed())->toBeTrue()
        ->and($nobody->refresh()->isProcessed())->toBeFalse();
});

it('pulls the provider for orders pending past the threshold', function (): void {
    config()->set('shopper.payment.reconciliation.pull_on_completion', false);

    $orderId = $this->postJson($this->completeUrl)->assertCreated()->json('data.id');
    $this->driver->retrievedStatus = 'captured';

    $this->artisan('shopper:payments:reconcile', ['--pull' => true])->assertSuccessful();

    $order = Order::query()->where('public_id', $orderId)->firstOrFail();

    expect($order->payment_status)->toBe(PaymentStatus::Pending)
        ->and($this->driver->retrievals)->toBe(0);

    $this->travel(16)->minutes();

    $this->artisan('shopper:payments:reconcile', ['--pull' => true])
        ->expectsOutputToContain('1 pending payment queued')
        ->assertSuccessful();

    expect($order->refresh()->payment_status)->toBe(PaymentStatus::Paid)
        ->and($this->driver->retrievals)->toBe(1);
});
