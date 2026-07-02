<?php

declare(strict_types=1);

use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Payment\DataTransferObjects\PaymentResult;
use Shopper\Payment\Drivers\Driver;
use Shopper\Payment\Enum\TransactionStatus;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Exceptions\PaymentException;
use Shopper\Payment\Facades\Payment;
use Shopper\Payment\Models\PaymentTransaction;
use Shopper\Payment\Services\PaymentProcessingService;
use Tests\Core\Stubs\User;

uses(Tests\Core\TestCase::class);

beforeEach(function (): void {
    $this->driverCalls = collect();

    $calls = $this->driverCalls;

    Payment::extend('guarded', fn (): Driver => new class($calls) extends Driver
    {
        public function __construct(
            private readonly Illuminate\Support\Collection $calls,
        ) {}

        public function code(): string
        {
            return 'guarded';
        }

        public function name(): string
        {
            return 'Guarded';
        }

        public function isConfigured(): bool
        {
            return true;
        }

        public function initiatePayment(int $amount, string $currency, array $context = []): PaymentResult
        {
            return new PaymentResult(success: true, status: 'pending', reference: 'pi_guard', amount: $amount);
        }

        public function capturePayment(string $reference, ?int $amount = null): PaymentResult
        {
            $this->calls->push('capture');

            return new PaymentResult(success: true, status: 'captured', reference: $reference, amount: $amount);
        }

        public function refundPayment(string $reference, int $amount, ?string $reason = null, array $context = []): PaymentResult
        {
            $this->calls->push('refund');

            return new PaymentResult(success: true, status: 'refunded', reference: $reference, amount: $amount);
        }
    });

    $this->method = PaymentMethod::factory()->create(['driver' => 'guarded']);
    $this->service = resolve(PaymentProcessingService::class);
});

function paidOrder(PaymentMethod $method, int $amount = 5000): Order
{
    $order = Order::factory()->create([
        'payment_method_id' => $method->id,
        'price_amount' => $amount,
        'currency_code' => 'USD',
        'payment_status' => PaymentStatus::Paid,
    ]);

    PaymentTransaction::query()->create([
        'order_id' => $order->id,
        'payment_method_id' => $method->id,
        'driver' => 'guarded',
        'type' => TransactionType::Capture,
        'status' => TransactionStatus::Success,
        'amount' => $amount,
        'currency_code' => 'USD',
        'reference' => 'pi_guard',
    ]);

    return $order;
}

it('rejects a refund on an order that was never paid', function (): void {
    $order = Order::factory()->create([
        'payment_method_id' => $this->method->id,
        'price_amount' => 5000,
        'payment_status' => PaymentStatus::Pending,
    ]);

    expect(fn () => $this->service->refund($order, 'pi_guard', amount: 1000))
        ->toThrow(PaymentException::class);

    expect($this->driverCalls)->toBeEmpty();
});

it('rejects a refund exceeding the captured amount', function (): void {
    $order = paidOrder($this->method);

    expect(fn () => $this->service->refund($order, 'pi_guard', amount: 999999))
        ->toThrow(PaymentException::class);

    expect($this->driverCalls)->toBeEmpty();
});

it('rejects a refund exceeding what remains after previous refunds', function (): void {
    $order = paidOrder($this->method);

    $this->service->refund($order, 'pi_guard', amount: 3000);

    expect(fn () => $this->service->refund($order, 'pi_guard', amount: 3000))
        ->toThrow(PaymentException::class);

    expect($this->driverCalls->all())->toBe(['refund']);
});

it('allows refunding exactly the remaining amount and marks the order refunded', function (): void {
    $order = paidOrder($this->method);

    $this->service->refund($order, 'pi_guard', amount: 3000);
    $this->service->refund($order, 'pi_guard', amount: 2000);

    expect($order->refresh()->payment_status)->toBe(PaymentStatus::Refunded);
});

it('caps the refund at the order total for a manually marked paid order', function (): void {
    $order = Order::factory()->create([
        'payment_method_id' => $this->method->id,
        'price_amount' => 5000,
        'currency_code' => 'USD',
        'payment_status' => PaymentStatus::Paid,
    ]);

    expect(fn () => $this->service->refund($order, 'pi_guard', amount: 5001))
        ->toThrow(PaymentException::class);

    $this->service->refund($order, 'pi_guard', amount: 5000);

    expect($order->refresh()->payment_status)->toBe(PaymentStatus::Refunded);
});

it('short-circuits a capture on an already paid order without calling the driver', function (): void {
    $order = paidOrder($this->method);

    $result = $this->service->capture($order, 'pi_guard');

    expect($result->success)->toBeTrue()
        ->and($this->driverCalls)->toBeEmpty()
        ->and(PaymentTransaction::query()->where('order_id', $order->id)->where('type', TransactionType::Capture)->count())->toBe(1);
});

it('records the acting user on payment transactions', function (): void {
    $order = paidOrder($this->method);
    $admin = User::factory()->create();
    $this->actingAs($admin);

    $this->service->refund($order, 'pi_guard', amount: 1000);

    $refund = PaymentTransaction::query()
        ->where('order_id', $order->id)
        ->where('type', TransactionType::Refund)
        ->first();

    expect($refund->user_id)->toBe($admin->id);
});
