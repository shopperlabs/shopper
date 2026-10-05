<?php

declare(strict_types=1);

use Illuminate\Console\Scheduling\Event as ScheduledEvent;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Sleep;
use Shopper\Cart\Listeners\RecognizeOrphanedPayment;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Contracts\Priceable;
use Shopper\Core\Contracts\QuantityRuleResolver;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Events\Orders\OrderPaid;
use Shopper\Core\Events\Payments\PaymentOrphaned;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\Zone;
use Shopper\Core\Pricing\PricingContext;
use Shopper\Core\Pricing\QuantityRule;
use Shopper\Payment\Actions\SettlePayment;
use Shopper\Payment\DataTransferObjects\WebhookResult;
use Shopper\Payment\Enum\TransactionStatus;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Enum\WebhookAction;
use Shopper\Payment\Exceptions\PaymentException;
use Shopper\Payment\Facades\Payment;
use Shopper\Payment\Models\PaymentTransaction;
use Shopper\Payment\Models\PaymentWebhookEvent;
use Tests\Api\Stubs\FakePaymentDriver;

uses(Tests\Api\TestCase::class);

/**
 * @param  array<string, mixed>  $data
 */
function earlyEvent(WebhookAction $action, string $reference, string $eventId, ?int $amount = 3200, array $data = []): PaymentWebhookEvent
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
    $this->product->prices()->create([
        'amount' => 2500,
        'currency_id' => Currency::query()->where('code', 'USD')->value('id'),
    ]);
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

it('schedules the reconciliation every fifteen minutes with an overlap lock that expires within the hour', function (): void {
    $event = collect(resolve(Schedule::class)->events())
        ->first(fn (ScheduledEvent $event): bool => str_contains((string) $event->command, 'shopper:payments:reconcile'));

    expect($event)
        ->expression->toBe('*/15 * * * *')
        ->withoutOverlapping->toBeTrue()
        ->expiresAt->toBe(60);
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

describe('orphaned payments', function (): void {
    beforeEach(function (): void {
        Exceptions::fake();
    });

    it('completes the cart a payment paid for once the storefront had its grace', function (array $data): void {
        $event = earlyEvent(WebhookAction::Captured, $this->reference, 'evt_orphan', data: $data);

        $this->artisan('shopper:payments:reconcile')->assertSuccessful();

        expect(Order::query()->count())->toBe(0);

        $this->travel(31)->minutes();

        $this->artisan('shopper:payments:reconcile')
            ->expectsOutputToContain('0 orphaned payments reported')
            ->assertSuccessful();

        $order = $this->cart->refresh()->order;

        expect($order->payment_status)->toBe(PaymentStatus::Paid)
            ->and($event->refresh()->isProcessed())->toBeTrue();
        Exceptions::assertNothingReported();
    })->with([
        'found by its reference' => [[]],
        'found by the cart id the provider sent back' => [fn (): array => ['cart_id' => $this->cart->public_id]],
        'found by its reference despite a legacy cart id' => [fn (): array => ['cart_id' => (string) $this->cart->id]],
    ]);

    it('finds the cart of a session opened before its reference had a column', function (): void {
        $migration = include __DIR__.'/../../../packages/cart/database/migrations/2026_10_02_000001_add_payment_reference_to_carts_table.php';
        $migration->down();
        $migration->up();
        earlyEvent(WebhookAction::Captured, $this->reference, 'evt_orphan');
        $this->travel(31)->minutes();

        $this->artisan('shopper:payments:reconcile')->assertSuccessful();

        expect($this->cart->refresh()->order)->not->toBeNull();
    });

    it('reports an orphan whose cart moved on to another payment', function (): void {
        $this->cart->refresh()->forceFill(['payment_session' => [...$this->cart->payment_session, 'reference' => 'pi_newer']])->save();
        earlyEvent(WebhookAction::Captured, $this->reference, 'evt_orphan', data: ['cart_id' => $this->cart->public_id]);
        $this->travel(31)->minutes();

        $this->artisan('shopper:payments:reconcile')
            ->expectsOutputToContain('1 orphaned payment reported')
            ->assertSuccessful();

        expect($this->cart->refresh()->isCompleted())->toBeFalse();
    });

    it('reports a second payment of a cart completed on its first one', function (): void {
        $this->postJson($this->completeUrl)->assertCreated();
        earlyEvent(WebhookAction::Captured, 'pi_second', 'evt_second', data: $this->driver->lastContext['metadata']);
        $this->travel(31)->minutes();

        $this->artisan('shopper:payments:reconcile')
            ->expectsOutputToContain('1 orphaned payment reported')
            ->assertSuccessful();
    });

    it('reports once an orphan whose cart cannot be completed, and keeps the money by default', function (): void {
        $this->cart->update(['email' => null]);
        $event = earlyEvent(WebhookAction::Captured, $this->reference, 'evt_orphan');
        $this->travel(31)->minutes();

        $this->artisan('shopper:payments:reconcile')
            ->expectsOutputToContain('1 orphaned payment reported')
            ->assertSuccessful();
        $this->artisan('shopper:payments:reconcile')->assertSuccessful();

        Exceptions::assertReportedCount(1);
        Exceptions::assertReported(fn (PaymentException $exception): bool => str_contains($exception->getMessage(), $this->reference));
        expect($event->refresh()->orphaned_at)->not->toBeNull()
            ->and($event->isProcessed())->toBeFalse()
            ->and($this->driver->refunds)->toBeEmpty();
    });

    it('reports once an orphan whose quantity rule refuses its line before the payment was collected', function (): void {
        $this->app->instance(QuantityRuleResolver::class, new class implements QuantityRuleResolver
        {
            public function resolve(Priceable&Model $purchasable, PricingContext $context): QuantityRule
            {
                return new QuantityRule(minimum: 2);
            }
        });
        $this->driver->retrievedStatus = 'canceled';
        $event = earlyEvent(WebhookAction::Authorized, $this->reference, 'evt_orphan');
        $this->travel(31)->minutes();

        $this->artisan('shopper:payments:reconcile')
            ->expectsOutputToContain('1 orphaned payment reported')
            ->assertSuccessful();
        $this->artisan('shopper:payments:reconcile')->assertSuccessful();

        Exceptions::assertReportedCount(1);
        expect($event->refresh()->orphaned_at)->not->toBeNull()
            ->and($this->cart->refresh()->isCompleted())->toBeFalse();
    });

    it('reports once an orphan whose line lost its price before the payment was collected', function (): void {
        $this->product->prices()->delete();
        $this->driver->retrievedStatus = 'canceled';
        $event = earlyEvent(WebhookAction::Authorized, $this->reference, 'evt_orphan');
        $this->travel(31)->minutes();

        $this->artisan('shopper:payments:reconcile')
            ->expectsOutputToContain('1 orphaned payment reported')
            ->assertSuccessful();
        $this->artisan('shopper:payments:reconcile')->assertSuccessful();

        Exceptions::assertReportedCount(1);
        expect($event->refresh()->orphaned_at)->not->toBeNull()
            ->and($this->cart->refresh()->isCompleted())->toBeFalse();
    });

    it('reports once an orphan its provider announced twice', function (): void {
        $this->cart->update(['email' => null]);
        earlyEvent(WebhookAction::Authorized, $this->reference, 'evt_authorized');
        earlyEvent(WebhookAction::Captured, $this->reference, 'evt_captured');
        $this->travel(31)->minutes();

        $this->artisan('shopper:payments:reconcile')
            ->expectsOutputToContain('1 orphaned payment reported')
            ->assertSuccessful();

        Exceptions::assertReportedCount(1);
    });

    it('gives an orphan back when the configuration asks for it', function (): void {
        config()->set('shopper.payment.reconciliation.orphans', 'refund');
        $this->cart->update(['email' => null]);
        $this->driver->retrievedStatus = 'captured';
        $event = earlyEvent(WebhookAction::Captured, $this->reference, 'evt_orphan');
        $this->travel(31)->minutes();

        $this->artisan('shopper:payments:reconcile')->assertSuccessful();

        expect($this->driver->refunds)->toHaveCount(1)
            ->and($this->driver->refunds[0]['reference'])->toBe($this->reference)
            ->and($event->refresh()->orphaned_at)->not->toBeNull()
            ->and($this->cart->refresh()->payment_session)->toBeNull();
    });

    it('keeps the newer payment session of a cart when its orphan is given back', function (): void {
        config()->set('shopper.payment.reconciliation.orphans', 'refund');
        $this->driver->retrievedStatus = 'captured';
        $this->cart->refresh()->forceFill(['payment_session' => [...$this->cart->payment_session, 'reference' => 'pi_newer']])->save();
        earlyEvent(WebhookAction::Captured, $this->reference, 'evt_orphan', data: ['cart_id' => $this->cart->public_id]);
        $this->travel(31)->minutes();

        $this->artisan('shopper:payments:reconcile')->assertSuccessful();

        expect($this->driver->refunds)->toHaveCount(1)
            ->and($this->driver->refunds[0]['reference'])->toBe($this->reference)
            ->and($this->cart->refresh()->payment_session['reference'])->toBe('pi_newer');
    });

    it('reports once an orphan its driver cannot give back', function (): void {
        config()->set('shopper.payment.reconciliation.orphans', 'refund');
        $this->cart->update(['email' => null]);
        $this->driver->retrieval = false;
        $event = earlyEvent(WebhookAction::Captured, $this->reference, 'evt_orphan');
        $this->travel(31)->minutes();

        $this->artisan('shopper:payments:reconcile')->assertSuccessful();
        $this->artisan('shopper:payments:reconcile')->assertSuccessful();

        Exceptions::assertReportedCount(1);
        expect($event->refresh()->orphaned_at)->not->toBeNull()
            ->and($this->driver->refunds)->toBeEmpty()
            ->and($this->cart->refresh()->payment_session['reference'])->toBe($this->reference);
    });

    it('reports the orphan of a store cart when no listener completes it', function (): void {
        Event::forget(PaymentOrphaned::class);
        Event::listen(PaymentOrphaned::class, RecognizeOrphanedPayment::class);
        earlyEvent(WebhookAction::Captured, $this->reference, 'evt_orphan');
        $this->travel(31)->minutes();

        $this->artisan('shopper:payments:reconcile')
            ->expectsOutputToContain('1 orphaned payment reported')
            ->assertSuccessful();

        expect($this->cart->refresh()->isCompleted())->toBeFalse();
    });

    it('tries again when the orphan cannot be given back yet', function (): void {
        config()->set('shopper.payment.reconciliation.orphans', 'refund');
        $this->cart->update(['email' => null]);
        $this->driver->retrievedStatus = 'processing';
        $event = earlyEvent(WebhookAction::Captured, $this->reference, 'evt_orphan');
        $this->travel(31)->minutes();

        $this->artisan('shopper:payments:reconcile')->assertSuccessful();

        expect($event->refresh()->orphaned_at)->toBeNull();
    });

    it('never reports a payment given back or a cart another request is completing', function (): void {
        Sleep::fake(syncWithCarbon: true);
        earlyEvent(WebhookAction::Captured, 'pi_returned', 'evt_captured');
        earlyEvent(WebhookAction::Refunded, 'pi_returned', 'evt_refunded');
        $busy = earlyEvent(WebhookAction::Captured, $this->reference, 'evt_busy');
        Cache::lock("cart:payment-session:{$this->cart->public_id}", 7200)->get();
        $this->travel(31)->minutes();

        $this->artisan('shopper:payments:reconcile')
            ->expectsOutputToContain('0 orphaned payments reported')
            ->assertSuccessful();

        expect($busy->refresh()->orphaned_at)->toBeNull()
            ->and($this->cart->refresh()->isCompleted())->toBeFalse();
        Exceptions::assertNothingReported();
    });

    it('reports once a partly refunded orphan without completing its cart or giving it back', function (): void {
        config()->set('shopper.payment.reconciliation.orphans', 'refund');
        $this->driver->retrievedStatus = 'captured';
        $event = earlyEvent(WebhookAction::Captured, $this->reference, 'evt_orphan', data: ['cart_id' => $this->cart->public_id]);
        earlyEvent(WebhookAction::Refunded, $this->reference, 'evt_refund_created', 1600, ['refund_id' => 're_partial']);
        earlyEvent(WebhookAction::Refunded, $this->reference, 'evt_refund_updated', 1600, ['refund_id' => 're_partial']);
        $this->travel(31)->minutes();

        $this->artisan('shopper:payments:reconcile')
            ->expectsOutputToContain('1 orphaned payment reported')
            ->assertSuccessful();
        $this->artisan('shopper:payments:reconcile')->assertSuccessful();

        Exceptions::assertReportedCount(1);
        expect($event->refresh()->orphaned_at)->not->toBeNull()
            ->and($this->driver->refunds)->toBeEmpty()
            ->and($this->cart->refresh()->isCompleted())->toBeFalse();
    });

    it('forgets the payment session of a cart whose payment is refunded before its order exists', function (?string $eventId, ?int $amount): void {
        $this->postJson('/store/webhooks/fake', [
            'action' => 'refunded',
            'reference' => $this->reference,
            'amount' => $amount,
            'event_id' => $eventId,
        ])->assertOk();

        expect($this->cart->refresh()->payment_session)->toBeNull();
    })->with([
        'journaled' => ['evt_refund', 3200],
        'journaled without an amount' => ['evt_refund', null],
        'without an event id' => [null, 3200],
    ]);

    it('keeps the payment session of a cart whose payment webhook arrives before its order', function (string $action, ?string $eventId, int $amount): void {
        $this->postJson('/store/webhooks/fake', [
            'action' => $action,
            'reference' => $this->reference,
            'amount' => $amount,
            'event_id' => $eventId,
        ])->assertOk();

        expect($this->cart->refresh()->payment_session['reference'])->toBe($this->reference);
    })->with([
        'captured' => ['captured', 'evt_early', 3200],
        'captured without an event id' => ['captured', null, 3200],
        'failed' => ['failed', 'evt_declined', 3200],
        'partly refunded' => ['refunded', 'evt_refund', 1600],
        'partly refunded without an event id' => ['refunded', null, 1600],
    ]);

    it('forgets the payment session once partial refunds add up to the payment', function (): void {
        $this->postJson('/store/webhooks/fake', ['action' => 'captured', 'reference' => $this->reference, 'amount' => 3200, 'event_id' => 'evt_captured'])->assertOk();
        $this->postJson('/store/webhooks/fake', ['action' => 'refunded', 'reference' => $this->reference, 'amount' => 1600, 'event_id' => 'evt_refund_1'])->assertOk();

        expect($this->cart->refresh()->payment_session['reference'])->toBe($this->reference);

        $this->postJson('/store/webhooks/fake', ['action' => 'refunded', 'reference' => $this->reference, 'amount' => 1600, 'event_id' => 'evt_refund_2'])->assertOk();

        expect($this->cart->refresh()->payment_session)->toBeNull();
    });

    it('forgets the payment session when a refund is redelivered after its first delivery failed', function (): void {
        earlyEvent(WebhookAction::Refunded, $this->reference, 'evt_refund');

        $this->postJson('/store/webhooks/fake', [
            'action' => 'refunded',
            'reference' => $this->reference,
            'amount' => 3200,
            'event_id' => 'evt_refund',
        ])->assertOk();

        expect($this->cart->refresh()->payment_session)->toBeNull();
    });

    it('leaves alone a payment no cart of the store paid with', function (array $data): void {
        config()->set('shopper.payment.reconciliation.orphans', 'refund');
        $this->driver->retrievedStatus = 'captured';
        $event = earlyEvent(WebhookAction::Captured, 'pi_elsewhere', 'evt_elsewhere', data: $data);
        $this->travel(31)->minutes();

        $this->artisan('shopper:payments:reconcile')
            ->expectsOutputToContain('0 orphaned payments reported')
            ->assertSuccessful();

        expect($event->refresh()->orphaned_at)->not->toBeNull()
            ->and($this->driver->refunds)->toBeEmpty();
        Exceptions::assertNothingReported();
    })->with([
        'unknown to the store' => [[]],
        'paid for a cart of another store' => [['cart_id' => 'cart_elsewhere']],
    ]);

    it('never reports a partly refunded payment that carries no cart', function (): void {
        $event = earlyEvent(WebhookAction::Captured, 'pi_elsewhere', 'evt_elsewhere');
        earlyEvent(WebhookAction::Refunded, 'pi_elsewhere', 'evt_elsewhere_refund', 1600);
        $this->travel(31)->minutes();

        $this->artisan('shopper:payments:reconcile')
            ->expectsOutputToContain('0 orphaned payments reported')
            ->assertSuccessful();

        expect($event->refresh()->orphaned_at)->not->toBeNull();
        Exceptions::assertNothingReported();
    });

    it('never reports a payment given back once the event that gave it back is pruned', function (WebhookAction $taken, WebhookAction $returned, ?int $amount): void {
        earlyEvent($taken, 'pi_returned', 'evt_taken', data: ['cart_id' => $this->cart->public_id]);
        earlyEvent($returned, 'pi_returned', 'evt_returned', $amount);
        $this->travel(31)->minutes();
        $this->artisan('shopper:payments:reconcile')->assertSuccessful();

        $this->travel(91)->days();
        (new PaymentWebhookEvent)->pruneAll();

        $this->artisan('shopper:payments:reconcile')
            ->expectsOutputToContain('0 orphaned payments reported')
            ->assertSuccessful();

        Exceptions::assertNothingReported();
    })->with([
        'refunded' => [WebhookAction::Captured, WebhookAction::Refunded, 3200],
        'canceled' => [WebhookAction::Authorized, WebhookAction::Canceled, null],
    ]);

    it('never reports an orphan whose payment its cart completion gave back', function (): void {
        $this->driver->retrievedStatus = 'captured';
        $this->product->decreaseStock($this->inventory->id, 100);
        $event = earlyEvent(WebhookAction::Captured, $this->reference, 'evt_orphan');
        $this->travel(31)->minutes();

        $this->artisan('shopper:payments:reconcile')->assertSuccessful();

        expect($this->driver->refunds)->toHaveCount(1)
            ->and($event->refresh()->orphaned_at)->not->toBeNull();
        Exceptions::assertNothingReported();
    });
});

it('prunes old events except the collected ones still waiting for their order', function (): void {
    $waiting = earlyEvent(WebhookAction::Captured, 'pi_waiting', 'evt_waiting');
    $applied = earlyEvent(WebhookAction::Captured, 'pi_applied', 'evt_applied');
    $applied->claim();
    $orphaned = earlyEvent(WebhookAction::Authorized, 'pi_orphaned', 'evt_orphaned');
    $orphaned->forceFill(['orphaned_at' => now()])->save();
    $failed = earlyEvent(WebhookAction::Failed, 'pi_failed', 'evt_failed');

    $this->travel(91)->days();

    expect((new PaymentWebhookEvent)->prunable()->pluck('id')->sort()->values()->all())
        ->toBe(collect([$applied->id, $orphaned->id, $failed->id])->sort()->values()->all());
});
