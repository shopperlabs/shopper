<?php

declare(strict_types=1);

use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Payment\DataTransferObjects\PaymentResult;
use Shopper\Payment\Drivers\Driver;
use Shopper\Payment\Exceptions\PaymentException;
use Shopper\Payment\Facades\Payment;
use Shopper\Payment\Services\PaymentProcessingService;

uses(Tests\Core\TestCase::class);

beforeEach(function (): void {
    $this->keys = collect();
    $this->shouldTimeout = false;

    $keys = $this->keys;
    $shouldTimeout = fn (): bool => $this->shouldTimeout;

    Payment::extend('recording', fn (): Driver => new class($keys, $shouldTimeout) extends Driver
    {
        public function __construct(
            private readonly Illuminate\Support\Collection $keys,
            private readonly Closure $shouldTimeout,
        ) {}

        public function code(): string
        {
            return 'recording';
        }

        public function name(): string
        {
            return 'Recording';
        }

        public function isConfigured(): bool
        {
            return true;
        }

        public function initiatePayment(int $amount, string $currency, array $context = []): PaymentResult
        {
            return new PaymentResult(success: true, status: 'pending', reference: 'pi_abc', amount: $amount);
        }

        public function refundPayment(string $reference, int $amount, ?string $reason = null, array $context = []): PaymentResult
        {
            $this->keys->push($context['idempotency_key'] ?? null);

            if (($this->shouldTimeout)()) {
                throw PaymentException::notSupported('timeout', $this->code());
            }

            return new PaymentResult(success: true, status: 'refunded', reference: $reference, amount: $amount);
        }
    });

    $method = PaymentMethod::factory()->create(['driver' => 'recording']);
    $this->order = Order::factory()->create([
        'payment_method_id' => $method->id,
        'price_amount' => 5000,
        'payment_status' => PaymentStatus::Paid,
    ]);
    $this->service = resolve(PaymentProcessingService::class);
});

it('gives every refund made without a key an idempotency key of its own', function (): void {
    $this->service->refund($this->order, 'pi_abc', amount: 1000);
    $this->service->refund($this->order, 'pi_abc', amount: 1000);

    expect($this->keys->filter()->unique())->toHaveCount(2);
});

it('sends the caller key again when the previous attempt was never journalized', function (): void {
    $this->shouldTimeout = true;

    expect(fn () => $this->service->refund($this->order, 'pi_abc', amount: 1000, idempotencyKey: 'req_1'))
        ->toThrow(PaymentException::class);

    $this->shouldTimeout = false;

    $this->service->refund($this->order, 'pi_abc', amount: 1000, idempotencyKey: 'req_1');

    expect($this->keys->all())->toBe(['req_1', 'req_1']);
});

it('refunds once when a key already journalized is submitted again', function (): void {
    $this->service->refund($this->order, 'pi_abc', amount: 1000, idempotencyKey: 'req_1');

    $retry = $this->service->refund($this->order, 'pi_abc', amount: 1000, idempotencyKey: 'req_1');

    expect($this->keys)->toHaveCount(1)
        ->and($retry->success)->toBeTrue()
        ->and($retry->amount)->toBe(1000)
        ->and($this->service->getTransactions($this->order))->toHaveCount(1);
});

it('refunds two partial amounts of the same payment under two keys', function (): void {
    $this->service->refund($this->order, 'pi_abc', amount: 1000, idempotencyKey: 'req_1');
    $this->service->refund($this->order, 'pi_abc', amount: 1000, idempotencyKey: 'req_2');

    expect($this->service->getTransactions($this->order))->toHaveCount(2);
});

it('scopes an idempotency key to its order', function (): void {
    $other = Order::factory()->create([
        'payment_method_id' => $this->order->payment_method_id,
        'price_amount' => 5000,
        'payment_status' => PaymentStatus::Paid,
    ]);

    $this->service->refund($this->order, 'pi_abc', amount: 1000, idempotencyKey: 'req_1');
    $this->service->refund($other, 'pi_def', amount: 1000, idempotencyKey: 'req_1');

    expect($this->keys)->toHaveCount(2)
        ->and($other->refresh()->payment_status)->toBe(PaymentStatus::PartiallyRefunded);
});
