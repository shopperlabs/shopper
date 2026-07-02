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

it('moves the refund idempotency key forward once a refund is journalized', function (): void {
    $this->service->refund($this->order, 'pi_abc', amount: 1000);
    $this->service->refund($this->order, 'pi_abc', amount: 1000);

    expect($this->keys->all())->toBe([
        "refund:{$this->order->id}:pi_abc:1000:0",
        "refund:{$this->order->id}:pi_abc:1000:1",
    ]);
});

it('reuses the same idempotency key when the previous attempt was never journalized', function (): void {
    $this->shouldTimeout = true;

    expect(fn () => $this->service->refund($this->order, 'pi_abc', amount: 1000))
        ->toThrow(PaymentException::class);

    $this->shouldTimeout = false;

    $this->service->refund($this->order, 'pi_abc', amount: 1000);

    expect($this->keys->unique()->count())->toBe(1);
});
