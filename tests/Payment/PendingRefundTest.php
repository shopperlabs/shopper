<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Exceptions;
use Shopper\Core\Actions\ReserveCampaignBudget;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Models\Campaign;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Payment\Actions\IngestPaymentEvent;
use Shopper\Payment\Actions\SettlePayment;
use Shopper\Payment\DataTransferObjects\PaymentResult;
use Shopper\Payment\DataTransferObjects\WebhookResult;
use Shopper\Payment\Drivers\Driver;
use Shopper\Payment\Enum\TransactionStatus;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Enum\WebhookAction;
use Shopper\Payment\Events\PaymentFailed;
use Shopper\Payment\Exceptions\PaymentException;
use Shopper\Payment\Facades\Payment;
use Shopper\Payment\Models\PaymentTransaction;
use Shopper\Payment\Models\PaymentWebhookEvent;
use Shopper\Payment\Services\PaymentProcessingService;
use Tests\Core\Stubs\User;

uses(Tests\Core\TestCase::class);

beforeEach(function (): void {
    $this->keys = collect();
    $this->beforeReturning = null;
    $this->settlesAtOnce = false;

    $keys = $this->keys;
    $settlesAtOnce = fn (): bool => $this->settlesAtOnce;
    $beforeReturning = function (string $refundId): void {
        $this->beforeReturning?->__invoke($refundId);
    };

    Payment::extend('deferred', fn (): Driver => new class($keys, $beforeReturning, $settlesAtOnce) extends Driver
    {
        public function __construct(
            private readonly Illuminate\Support\Collection $keys,
            private readonly Closure $beforeReturning,
            private readonly Closure $settlesAtOnce,
        ) {}

        public function code(): string
        {
            return 'deferred';
        }

        public function name(): string
        {
            return 'Deferred';
        }

        public function isConfigured(): bool
        {
            return true;
        }

        public function initiatePayment(int $amount, string $currency, array $context = []): PaymentResult
        {
            return new PaymentResult(success: true, status: 'pending', reference: 'pay_1', amount: $amount);
        }

        public function refundPayment(string $reference, int $amount, ?string $reason = null, array $context = []): PaymentResult
        {
            $this->keys->push($context['idempotency_key'] ?? null);

            $refundId = 'rf_'.($this->keys->unique()->values()->search($context['idempotency_key'] ?? null) + 1);

            ($this->beforeReturning)($refundId);

            return ($this->settlesAtOnce)()
                ? new PaymentResult(success: true, status: 'refunded', reference: $refundId, amount: $amount)
                : PaymentResult::pendingRefund($refundId, $amount);
        }
    });

    $this->method = PaymentMethod::factory()->create(['driver' => 'deferred']);
    $this->order = Order::factory()->create([
        'payment_method_id' => $this->method->id,
        'price_amount' => 5000,
        'currency_code' => 'USD',
        'payment_status' => PaymentStatus::Paid,
    ]);

    PaymentTransaction::query()->create([
        'order_id' => $this->order->id,
        'payment_method_id' => $this->method->id,
        'driver' => 'deferred',
        'type' => TransactionType::Capture,
        'status' => TransactionStatus::Success,
        'amount' => 5000,
        'currency_code' => 'USD',
        'reference' => 'pay_1',
    ]);

    $this->service = resolve(PaymentProcessingService::class);
    $this->ingest = resolve(IngestPaymentEvent::class);
});

function pendingRefundRows(Order $order): Illuminate\Support\Collection
{
    return PaymentTransaction::query()
        ->where('order_id', $order->id)
        ->where('type', TransactionType::Refund)
        ->orderBy('id')
        ->get();
}

function pendingRefundWebhook(WebhookAction $action, string $refundId, int $amount, string $eventId): WebhookResult
{
    return new WebhookResult(
        action: $action,
        reference: 'pay_1',
        amount: $amount,
        data: ['refund_id' => $refundId, 'failure_message' => $action === WebhookAction::RefundFailed ? 'account_closed' : null],
        eventId: $eventId,
    );
}

it('journalizes a pending refund without failing it or touching the payment status', function (): void {
    Event::fake([PaymentFailed::class]);

    $result = $this->service->refund($this->order, 'pay_1', amount: 2000);

    $rows = pendingRefundRows($this->order);

    expect($result->isPending())->toBeTrue()
        ->and($rows)->toHaveCount(1)
        ->and($rows[0]->status)->toBe(TransactionStatus::Pending)
        ->and($rows[0]->reference)->toBe('rf_1')
        ->and($rows[0]->amount)->toBe(2000)
        ->and($this->order->refresh()->payment_status)->toBe(PaymentStatus::Paid);

    Event::assertNotDispatched(PaymentFailed::class);
});

it('counts a pending refund against what remains refundable', function (): void {
    $this->service->refund($this->order, 'pay_1', amount: 3000);

    expect(fn () => $this->service->refund($this->order, 'pay_1', amount: 3000))
        ->toThrow(PaymentException::class);

    $this->service->refund($this->order, 'pay_1', amount: 2000);

    expect($this->keys)->toHaveCount(2)
        ->and($this->order->refresh()->payment_status)->toBe(PaymentStatus::Paid);
});

it('sends the caller idempotency key to the provider and keeps it on the refund', function (): void {
    $this->service->refund($this->order, 'pay_1', amount: 1000, idempotencyKey: 'req_1');

    expect($this->keys->all())->toBe(['req_1'])
        ->and(pendingRefundRows($this->order)->sole()->idempotency_key)->toBe('req_1');
});

it('answers a repeated key with the refund on file without asking the provider again', function (): void {
    $this->service->refund($this->order, 'pay_1', amount: 2000, idempotencyKey: 'req_1');

    $retry = $this->service->refund($this->order, 'pay_1', amount: 2000, idempotencyKey: 'req_1');

    expect($this->keys)->toHaveCount(1)
        ->and(pendingRefundRows($this->order))->toHaveCount(1)
        ->and($retry->isPending())->toBeTrue()
        ->and($retry->reference)->toBe('rf_1')
        ->and($retry->amount)->toBe(2000);
});

it('answers a repeated key even once the refundable balance is used up', function (): void {
    $this->service->refund($this->order, 'pay_1', amount: 5000, idempotencyKey: 'req_1');

    $retry = $this->service->refund($this->order, 'pay_1', amount: 5000, idempotencyKey: 'req_1');

    expect($retry->reference)->toBe('rf_1')
        ->and($this->keys)->toHaveCount(1);
});

it('refuses a key that already names a refund of another amount', function (): void {
    $this->service->refund($this->order, 'pay_1', amount: 2000, idempotencyKey: 'req_1');

    expect(fn () => $this->service->refund($this->order, 'pay_1', amount: 3000, idempotencyKey: 'req_1'))
        ->toThrow(PaymentException::class, 'another amount');

    expect($this->keys)->toHaveCount(1)
        ->and(pendingRefundRows($this->order)->sole()->amount)->toBe(2000);
});

it('journalizes on its own row a second refund the provider issued for one key', function (): void {
    Exceptions::fake();

    $this->service->refund($this->order, 'pay_1', amount: 2000, idempotencyKey: 'req_1');

    $this->ingest->execute('deferred', new WebhookResult(
        action: WebhookAction::Refunded,
        reference: 'pay_1',
        amount: 2000,
        data: ['refund_id' => 'rf_lost', 'idempotency_key' => 'req_1'],
        eventId: 'evt_1',
    ));

    $rows = pendingRefundRows($this->order);

    expect($rows->pluck('reference')->all())->toBe(['rf_1', 'rf_lost'])
        ->and($rows->pluck('status')->all())->toBe([TransactionStatus::Pending, TransactionStatus::Success])
        ->and($rows->pluck('idempotency_key')->all())->toBe(['req_1', null])
        ->and(fn () => $this->service->refund($this->order, 'pay_1', amount: 1001))
        ->toThrow(PaymentException::class, 'only 1000 remains refundable');

    Exceptions::assertReported(fn (PaymentException $exception): bool => str_contains($exception->getMessage(), 'second refund'));
});

it('issues a new refund for every call made without a key', function (): void {
    $this->service->refund($this->order, 'pay_1', amount: 1000);
    $this->service->refund($this->order, 'pay_1', amount: 1000);

    expect($this->keys->unique())->toHaveCount(2)
        ->and(pendingRefundRows($this->order)->pluck('reference')->all())->toBe(['rf_1', 'rf_2']);
});

it('issues a distinct refund of the same amount under another key after a lost attempt', function (): void {
    $this->beforeReturning = fn () => throw PaymentException::apiError('deferred', 'timeout');

    expect(fn () => $this->service->refund($this->order, 'pay_1', amount: 2000, idempotencyKey: 'req_1'))
        ->toThrow(PaymentException::class, 'timeout');

    $this->beforeReturning = null;

    $result = $this->service->refund($this->order, 'pay_1', amount: 2000, idempotencyKey: 'req_2');

    expect($this->keys->all())->toBe(['req_1', 'req_2'])
        ->and($result->reference)->toBe('rf_2');
});

it('answers the retry of a refund settled meanwhile with its outcome', function (WebhookAction $action, bool $success, string $status): void {
    Event::fake([PaymentFailed::class]);

    $this->service->refund($this->order, 'pay_1', amount: 2000, idempotencyKey: 'req_1');
    $this->ingest->execute('deferred', pendingRefundWebhook($action, 'rf_1', 2000, 'evt_1'));

    $retry = $this->service->refund($this->order, 'pay_1', amount: 2000, idempotencyKey: 'req_1');

    expect($retry->success)->toBe($success)
        ->and($retry->status)->toBe($status)
        ->and($retry->reference)->toBe('rf_1')
        ->and($this->keys)->toHaveCount(1)
        ->and(pendingRefundRows($this->order))->toHaveCount(1);

    Event::assertDispatchedTimes(PaymentFailed::class, $success ? 0 : 1);
})->with([
    [WebhookAction::Refunded, true, 'refunded'],
    [WebhookAction::RefundFailed, false, 'failed'],
]);

it('settles the pending row when the refunded webhook arrives', function (): void {
    $this->service->refund($this->order, 'pay_1', amount: 2000);

    $pending = pendingRefundRows($this->order)->sole();

    expect($pending->status)->toBe(TransactionStatus::Pending);

    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::Refunded, 'rf_1', 2000, 'evt_1'));

    $rows = pendingRefundRows($this->order);

    expect($rows)->toHaveCount(1)
        ->and($rows[0]->id)->toBe($pending->id)
        ->and($rows[0]->status)->toBe(TransactionStatus::Success)
        ->and($this->order->refresh()->payment_status)->toBe(PaymentStatus::PartiallyRefunded);
});

it('marks the order refunded and releases the campaign budget once the last pending refund settles', function (): void {
    $campaign = Campaign::factory()->withSpendBudget(amount: 100_000)->create();
    resolve(ReserveCampaignBudget::class)->execute($campaign, spend: 2000, orderId: $this->order->id);

    $this->service->refund($this->order, 'pay_1', amount: 5000);

    expect($campaign->fresh()->spent_amount)->toBe(2000);

    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::Refunded, 'rf_1', 5000, 'evt_1'));

    expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Refunded)
        ->and($campaign->fresh()->spent_amount)->toBe(0);
});

it('changes nothing when the refunded webhook is replayed', function (): void {
    $this->service->refund($this->order, 'pay_1', amount: 2000);

    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::Refunded, 'rf_1', 2000, 'evt_1'));

    $settled = pendingRefundRows($this->order)->sole();

    $this->travel(5)->minutes();
    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::Refunded, 'rf_1', 2000, 'evt_2'));

    $rows = pendingRefundRows($this->order);

    expect($rows)->toHaveCount(1)
        ->and($rows[0]->status)->toBe(TransactionStatus::Success)
        ->and($rows[0]->updated_at->equalTo($settled->updated_at))->toBeTrue()
        ->and($this->order->refresh()->payment_status)->toBe(PaymentStatus::PartiallyRefunded);

    $this->service->refund($this->order, 'pay_1', amount: 3000);

    expect(pendingRefundRows($this->order)->pluck('status')->all())
        ->toBe([TransactionStatus::Success, TransactionStatus::Pending]);
});

it('keeps a single row when the webhook lands before refund() writes its own', function (): void {
    $this->beforeReturning = fn (string $refundId) => $this->ingest->execute(
        'deferred',
        pendingRefundWebhook(WebhookAction::Refunded, $refundId, 2000, 'evt_early'),
    );

    $this->service->refund($this->order, 'pay_1', amount: 2000);

    $rows = pendingRefundRows($this->order);

    expect($rows)->toHaveCount(1)
        ->and($rows[0]->status)->toBe(TransactionStatus::Success)
        ->and($rows[0]->amount)->toBe(2000)
        ->and($this->order->refresh()->payment_status)->toBe(PaymentStatus::PartiallyRefunded);

    $this->beforeReturning = null;

    expect(fn () => $this->service->refund($this->order, 'pay_1', amount: 3001))
        ->toThrow(PaymentException::class);
});

it('fails the pending row and notifies once when the refund fails by webhook', function (): void {
    Event::fake([PaymentFailed::class]);

    $this->service->refund($this->order, 'pay_1', amount: 2000);

    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::RefundFailed, 'rf_1', 2000, 'evt_1'));
    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::RefundFailed, 'rf_1', 2000, 'evt_2'));

    $rows = pendingRefundRows($this->order);

    expect($rows)->toHaveCount(1)
        ->and($rows[0]->status)->toBe(TransactionStatus::Failed)
        ->and($rows[0]->notes)->toBe('account_closed')
        ->and($this->order->refresh()->payment_status)->toBe(PaymentStatus::Paid);

    Event::assertDispatchedTimes(PaymentFailed::class, 1);
    Event::assertDispatched(
        PaymentFailed::class,
        fn (PaymentFailed $event): bool => $event->order->is($this->order)
            && $event->type === TransactionType::Refund
            && $event->reason === 'account_closed'
    );
});

it('gives the amount back to the refundable total once the pending refund fails', function (): void {
    $this->service->refund($this->order, 'pay_1', amount: 5000);

    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::RefundFailed, 'rf_1', 5000, 'evt_1'));

    $this->service->refund($this->order, 'pay_1', amount: 5000);

    expect(pendingRefundRows($this->order)->pluck('status')->all())
        ->toBe([TransactionStatus::Failed, TransactionStatus::Pending]);
});

it('fails a refund the provider takes back after confirming it and reopens the order', function (): void {
    Event::fake([PaymentFailed::class]);

    $campaign = Campaign::factory()->withSpendBudget(amount: 100_000)->create();
    resolve(ReserveCampaignBudget::class)->execute($campaign, spend: 5000, orderId: $this->order->id);

    $this->service->refund($this->order, 'pay_1', amount: 5000);

    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::Refunded, 'rf_1', 5000, 'evt_1'));

    expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Refunded);

    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::RefundFailed, 'rf_1', 5000, 'evt_2'));

    $refund = pendingRefundRows($this->order)->sole();

    expect($refund->status)->toBe(TransactionStatus::Failed)
        ->and($refund->notes)->toBe('account_closed')
        ->and($this->order->refresh()->payment_status)->toBe(PaymentStatus::Paid)
        ->and($campaign->fresh()->spent_amount)->toBe(0);

    Event::assertDispatchedTimes(PaymentFailed::class, 1);

    $this->service->refund($this->order, 'pay_1', amount: 5000);

    expect(pendingRefundRows($this->order))->toHaveCount(2);
});

it('keeps the order partially refunded when one of two confirmed refunds is taken back', function (): void {
    $this->service->refund($this->order, 'pay_1', amount: 2000);
    $this->service->refund($this->order, 'pay_1', amount: 3000);

    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::Refunded, 'rf_1', 2000, 'evt_1'));
    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::Refunded, 'rf_2', 3000, 'evt_2'));
    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::RefundFailed, 'rf_2', 3000, 'evt_3'));

    expect(pendingRefundRows($this->order)->pluck('status', 'reference')->all())
        ->toBe(['rf_1' => TransactionStatus::Success, 'rf_2' => TransactionStatus::Failed])
        ->and($this->order->refresh()->payment_status)->toBe(PaymentStatus::PartiallyRefunded);
});

it('notifies once when the failure of a confirmed refund is replayed', function (): void {
    Event::fake([PaymentFailed::class]);

    $this->service->refund($this->order, 'pay_1', amount: 2000);

    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::Refunded, 'rf_1', 2000, 'evt_1'));
    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::RefundFailed, 'rf_1', 2000, 'evt_2'));
    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::RefundFailed, 'rf_1', 2000, 'evt_3'));

    expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Paid);

    Event::assertDispatchedTimes(PaymentFailed::class, 1);
});

it('keeps a single failed row when the failure lands before refund() writes its own', function (): void {
    Event::fake([PaymentFailed::class]);

    $this->beforeReturning = fn (string $refundId) => $this->ingest->execute(
        'deferred',
        pendingRefundWebhook(WebhookAction::RefundFailed, $refundId, 2000, 'evt_early'),
    );

    $result = $this->service->refund($this->order, 'pay_1', amount: 2000);

    $rows = pendingRefundRows($this->order);

    expect($rows)->toHaveCount(1)
        ->and($rows[0]->status)->toBe(TransactionStatus::Failed)
        ->and($result->success)->toBeFalse()
        ->and($result->message)->toBe('account_closed');

    Event::assertDispatchedTimes(PaymentFailed::class, 1);
});

it('ends on the failure when the events of a refund are replayed out of order', function (): void {
    $this->service->refund($this->order, 'pay_1', amount: 2000);

    PaymentWebhookEvent::journal('deferred', pendingRefundWebhook(WebhookAction::RefundFailed, 'rf_1', 2000, 'evt_1'));
    PaymentWebhookEvent::journal('deferred', pendingRefundWebhook(WebhookAction::Refunded, 'rf_1', 2000, 'evt_2'));

    resolve(SettlePayment::class)->execute('pay_1');

    expect(pendingRefundRows($this->order)->sole()->status)->toBe(TransactionStatus::Failed)
        ->and($this->order->refresh()->payment_status)->toBe(PaymentStatus::Paid);
});

it('keeps a single row when the webhook confirms a refund settled at once before refund() writes its own', function (): void {
    $admin = User::factory()->create();
    $this->actingAs($admin);

    $this->settlesAtOnce = true;
    $this->beforeReturning = fn (string $refundId) => $this->ingest->execute(
        'deferred',
        pendingRefundWebhook(WebhookAction::Refunded, $refundId, 2500, 'evt_early'),
    );

    $this->service->refund($this->order, 'pay_1', amount: 2500);

    $refund = pendingRefundRows($this->order)->sole();

    expect($refund->status)->toBe(TransactionStatus::Success)
        ->and($refund->user_id)->toBe($admin->id)
        ->and($this->order->refresh()->payment_status)->toBe(PaymentStatus::PartiallyRefunded);

    $this->service->refund($this->order, 'pay_1', amount: 2500);

    expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Refunded);
});

it('refunds once when a retry follows a lost attempt its webhook already journalized', function (): void {
    $admin = User::factory()->create();
    $this->actingAs($admin);

    $this->settlesAtOnce = true;
    $this->beforeReturning = fn () => throw PaymentException::apiError('deferred', 'timeout');

    expect(fn () => $this->service->refund($this->order, 'pay_1', amount: 2000, idempotencyKey: 'req_1'))
        ->toThrow(PaymentException::class, 'timeout');

    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::Refunded, 'rf_1', 2000, 'evt_1'));

    $this->beforeReturning = null;
    $retry = $this->service->refund($this->order, 'pay_1', amount: 2000, idempotencyKey: 'req_1');

    $refund = pendingRefundRows($this->order)->sole();

    expect($this->keys->all())->toBe(['req_1', 'req_1'])
        ->and($retry->status)->toBe('refunded')
        ->and($refund->reference)->toBe('rf_1')
        ->and($refund->idempotency_key)->toBe('req_1')
        ->and($refund->user_id)->toBe($admin->id)
        ->and($this->order->refresh()->payment_status)->toBe(PaymentStatus::PartiallyRefunded);

    $this->service->refund($this->order, 'pay_1', amount: 2000, idempotencyKey: 'req_1');

    expect($this->keys)->toHaveCount(2);

    $this->service->refund($this->order, 'pay_1', amount: 2000, idempotencyKey: 'req_2');

    expect(pendingRefundRows($this->order)->pluck('reference')->all())->toBe(['rf_1', 'rf_2']);
});

it('finds a lost attempt by the key its webhook hands back, without asking the provider again', function (): void {
    $this->beforeReturning = fn () => throw PaymentException::apiError('deferred', 'timeout');

    expect(fn () => $this->service->refund($this->order, 'pay_1', amount: 2000, idempotencyKey: 'req_1'))
        ->toThrow(PaymentException::class, 'timeout');

    $this->ingest->execute('deferred', new WebhookResult(
        action: WebhookAction::Refunded,
        reference: 'pay_1',
        amount: 2000,
        data: ['refund_id' => 'rf_1', 'idempotency_key' => 'req_1'],
        eventId: 'evt_1',
    ));

    $this->beforeReturning = null;
    $retry = $this->service->refund($this->order, 'pay_1', amount: 2000, idempotencyKey: 'req_1');

    expect($this->keys)->toHaveCount(1)
        ->and($retry->status)->toBe('refunded')
        ->and($retry->reference)->toBe('rf_1')
        ->and(pendingRefundRows($this->order))->toHaveCount(1);
});

it('keeps the metadata of a refund the webhook journalized first', function (): void {
    $this->beforeReturning = function (string $refundId): void {
        $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::Refunded, $refundId, 1000, 'evt_early'));

        pendingRefundRows($this->order)->sole()->update(['metadata' => ['note' => 'kept']]);
    };

    $this->service->refund($this->order, 'pay_1', amount: 1000, idempotencyKey: 'req_1');

    $refund = pendingRefundRows($this->order)->sole();

    expect($refund->metadata)->toBe(['note' => 'kept'])
        ->and($refund->idempotency_key)->toBe('req_1');
});

it('reopens the order when a refund confirmed only by webhook is taken back', function (): void {
    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::Refunded, 'rf_dashboard', 5000, 'evt_1'));

    expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Refunded);

    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::RefundFailed, 'rf_dashboard', 5000, 'evt_2'));

    expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Paid)
        ->and(pendingRefundRows($this->order)->sole()->status)->toBe(TransactionStatus::Failed);
});

it('leaves the payment status alone when the failure of an unknown refund reaches an order that is not refunded', function (PaymentStatus $status): void {
    Order::query()->whereKey($this->order->id)->update(['payment_status' => $status]);

    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::RefundFailed, 'rf_dashboard', 5000, 'evt_1'));

    expect($this->order->refresh()->payment_status)->toBe($status);
})->with([PaymentStatus::Voided, PaymentStatus::Pending]);

it('applies a refund failure after the confirmation when both are replayed', function (): void {
    expect(WebhookAction::RefundFailed->precedence())->toBeGreaterThan(WebhookAction::Refunded->precedence());
});

it('completes a delivered order once its refund is taken back', function (): void {
    $this->order->update(['status' => OrderStatus::Processing]);

    $this->service->refund($this->order, 'pay_1', amount: 5000);

    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::Refunded, 'rf_1', 5000, 'evt_1'));
    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::RefundFailed, 'rf_1', 5000, 'evt_2'));

    expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Paid)
        ->and($this->order->status)->toBe(OrderStatus::Completed);
});

it('calls the provider inside the refund transaction', function (): void {
    $outside = DB::transactionLevel();
    $inside = null;

    $this->beforeReturning = function () use (&$inside): void {
        $inside = DB::transactionLevel();
    };

    $this->service->refund($this->order, 'pay_1', amount: 1000);

    expect($inside)->toBe($outside + 1);
});

it('checks the stored payment status, not the one the caller still holds', function (): void {
    Order::query()->whereKey($this->order->id)->update(['payment_status' => PaymentStatus::Voided]);

    expect(fn () => $this->service->refund($this->order, 'pay_1', amount: 1000))
        ->toThrow(PaymentException::class);

    expect($this->keys)->toBeEmpty();
});

it('keeps a failed refund failed when an older confirmation arrives after the failure', function (): void {
    $this->service->refund($this->order, 'pay_1', amount: 5000);

    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::RefundFailed, 'rf_1', 5000, 'evt_1'));
    $this->service->refund($this->order, 'pay_1', amount: 5000);
    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::Refunded, 'rf_1', 5000, 'evt_2'));

    expect(pendingRefundRows($this->order)->pluck('status')->all())
        ->toBe([TransactionStatus::Failed, TransactionStatus::Pending])
        ->and($this->order->refresh()->payment_status)->toBe(PaymentStatus::Paid);
});

it('reports a refund failure that names no refund and leaves the pending refund alone', function (): void {
    Event::fake([PaymentFailed::class]);
    Exceptions::fake();

    $this->service->refund($this->order, 'pay_1', amount: 2000);

    $this->ingest->execute('deferred', new WebhookResult(
        action: WebhookAction::RefundFailed,
        reference: 'pay_1',
        amount: 2000,
        eventId: 'evt_1',
    ));

    expect(pendingRefundRows($this->order)->sole()->status)->toBe(TransactionStatus::Pending)
        ->and(fn () => $this->service->refund($this->order, 'pay_1', amount: 3001))
        ->toThrow(PaymentException::class, 'only 3000 remains refundable');

    Event::assertNotDispatched(PaymentFailed::class);
    Exceptions::assertReported(fn (PaymentException $exception): bool => str_contains($exception->getMessage(), '[deferred]'));
});

it('fails the pending refund a failure names only by its idempotency key', function (): void {
    $this->service->refund($this->order, 'pay_1', amount: 2000, idempotencyKey: 'req_1');

    $this->ingest->execute('deferred', new WebhookResult(
        action: WebhookAction::RefundFailed,
        reference: 'pay_1',
        amount: 2000,
        data: ['idempotency_key' => 'req_1'],
        eventId: 'evt_1',
    ));

    $refund = pendingRefundRows($this->order)->sole();

    expect($refund->status)->toBe(TransactionStatus::Failed)
        ->and($refund->reference)->toBe('rf_1');
});

it('journalizes the failure of a refund the shop never issued with the amount the provider reports', function (): void {
    Event::fake([PaymentFailed::class]);

    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::RefundFailed, 'rf_dashboard', 1500, 'evt_1'));

    $failed = pendingRefundRows($this->order)->sole();

    expect($failed->status)->toBe(TransactionStatus::Failed)
        ->and($failed->amount)->toBe(1500)
        ->and($failed->reference)->toBe('rf_dashboard')
        ->and($this->order->refresh()->payment_status)->toBe(PaymentStatus::Paid);

    Event::assertDispatchedTimes(PaymentFailed::class, 1);

    $this->service->refund($this->order, 'pay_1', amount: 5000);

    expect(pendingRefundRows($this->order))->toHaveCount(2);
});

it('settles only the matching refund when two are pending', function (): void {
    $this->service->refund($this->order, 'pay_1', amount: 2000);
    $this->service->refund($this->order, 'pay_1', amount: 1000);

    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::Refunded, 'rf_2', 1000, 'evt_1'));

    expect(pendingRefundRows($this->order)->pluck('status', 'reference')->all())
        ->toBe(['rf_1' => TransactionStatus::Pending, 'rf_2' => TransactionStatus::Success])
        ->and($this->order->refresh()->payment_status)->toBe(PaymentStatus::PartiallyRefunded)
        ->and(fn () => $this->service->refund($this->order, 'pay_1', amount: 2001))->toThrow(PaymentException::class);

    $this->service->refund($this->order, 'pay_1', amount: 2000);

    expect(pendingRefundRows($this->order))->toHaveCount(3);
});

it('keeps the order partially refunded and its campaign budget while part of the capture is pending', function (): void {
    $campaign = Campaign::factory()->withSpendBudget(amount: 100_000)->create();
    resolve(ReserveCampaignBudget::class)->execute($campaign, spend: 2000, orderId: $this->order->id);

    $this->service->refund($this->order, 'pay_1', amount: 2000);
    $this->service->refund($this->order, 'pay_1', amount: 3000);

    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::Refunded, 'rf_1', 2000, 'evt_1'));

    expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::PartiallyRefunded)
        ->and($campaign->fresh()->spent_amount)->toBe(2000);

    $this->ingest->execute('deferred', pendingRefundWebhook(WebhookAction::Refunded, 'rf_2', 3000, 'evt_2'));

    expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Refunded)
        ->and($campaign->fresh()->spent_amount)->toBe(0);
});

it('records the acting user on a pending refund', function (): void {
    $admin = User::factory()->create();
    $this->actingAs($admin);

    $this->service->refund($this->order, 'pay_1', amount: 1000);

    expect(pendingRefundRows($this->order)->sole()->user_id)->toBe($admin->id);
});

it('puts the acting user back on a refund the webhook journalized first', function (): void {
    $admin = User::factory()->create();
    $this->actingAs($admin);

    $this->beforeReturning = fn (string $refundId) => $this->ingest->execute(
        'deferred',
        pendingRefundWebhook(WebhookAction::Refunded, $refundId, 1000, 'evt_early'),
    );

    $this->service->refund($this->order, 'pay_1', amount: 1000);

    expect(pendingRefundRows($this->order)->sole()->user_id)->toBe($admin->id);
});

it('falls back to a generic reason when the provider gives none', function (): void {
    Event::fake([PaymentFailed::class]);

    $this->service->refund($this->order, 'pay_1', amount: 2000);

    $this->ingest->execute('deferred', new WebhookResult(
        action: WebhookAction::RefundFailed,
        reference: 'pay_1',
        amount: 2000,
        data: ['refund_id' => 'rf_1'],
        eventId: 'evt_1',
    ));

    expect(pendingRefundRows($this->order)->sole()->notes)->toBe('Refund failed.');

    Event::assertDispatched(PaymentFailed::class, fn (PaymentFailed $event): bool => $event->reason === 'Refund failed.');
});

it('journalizes a pending result that names no refund of its own as a settled refund', function (?string $returned): void {
    Payment::extend('unreferenced', fn (): Driver => new class($returned) extends Driver
    {
        public function __construct(private readonly ?string $returned) {}

        public function code(): string
        {
            return 'unreferenced';
        }

        public function name(): string
        {
            return 'Unreferenced';
        }

        public function isConfigured(): bool
        {
            return true;
        }

        public function initiatePayment(int $amount, string $currency, array $context = []): PaymentResult
        {
            return new PaymentResult(success: true, status: 'pending', reference: 'pay_1', amount: $amount);
        }

        public function refundPayment(string $reference, int $amount, ?string $reason = null, array $context = []): PaymentResult
        {
            return new PaymentResult(success: true, status: 'pending', reference: $this->returned, amount: $amount);
        }
    });

    $this->method->update(['driver' => 'unreferenced']);

    $this->service->refund($this->order->refresh(), 'pay_1', amount: 2000);

    expect(pendingRefundRows($this->order)->sole()->status)->toBe(TransactionStatus::Success)
        ->and($this->order->refresh()->payment_status)->toBe(PaymentStatus::PartiallyRefunded);
})->with([null, 'pay_1']);

it('leaves alone the pending refund of a driver that cannot retrieve refunds', function (): void {
    Exceptions::fake();
    $this->service->refund($this->order, 'pay_1', amount: 2000);
    $this->travel(16)->minutes();

    $this->artisan('shopper:payments:reconcile', ['--pull' => true])
        ->expectsOutputToContain('0 pending refunds settled')
        ->assertSuccessful();

    expect(pendingRefundRows($this->order)->sole()->status)->toBe(TransactionStatus::Pending);
    Exceptions::assertNothingReported();
});
