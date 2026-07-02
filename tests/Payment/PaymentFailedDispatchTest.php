<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Payment\DataTransferObjects\PaymentResult;
use Shopper\Payment\Drivers\Driver;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Events\PaymentFailed;
use Shopper\Payment\Facades\Payment;
use Shopper\Payment\Services\PaymentProcessingService;

uses(Tests\Core\TestCase::class);

beforeEach(function (): void {
    Payment::extend('failing', fn (): Driver => new class extends Driver
    {
        public function code(): string
        {
            return 'failing';
        }

        public function name(): string
        {
            return 'Failing';
        }

        public function isConfigured(): bool
        {
            return true;
        }

        public function initiatePayment(int $amount, string $currency, array $context = []): PaymentResult
        {
            return PaymentResult::failed('not_initiated');
        }

        public function capturePayment(string $reference, ?int $amount = null): PaymentResult
        {
            return PaymentResult::failed('card_declined', $reference);
        }

        public function refundPayment(string $reference, int $amount, ?string $reason = null, array $context = []): PaymentResult
        {
            return PaymentResult::failed('insufficient_funds', $reference);
        }
    });
});

it('dispatches `PaymentFailed` when a capture fails', function (): void {
    Event::fake([PaymentFailed::class]);

    $method = PaymentMethod::factory()->create(['driver' => 'failing']);
    $order = Order::factory()->create(['payment_method_id' => $method->id]);

    resolve(PaymentProcessingService::class)->capture($order, 'ref_123', amount: 1000);

    Event::assertDispatched(
        PaymentFailed::class,
        fn (PaymentFailed $event): bool => $event->order->is($order) && $event->reason === 'card_declined'
    );
});

it('dispatches `PaymentFailed` when a refund fails', function (): void {
    Event::fake([PaymentFailed::class]);

    $method = PaymentMethod::factory()->create(['driver' => 'failing']);
    $order = Order::factory()->create(['payment_method_id' => $method->id]);

    resolve(PaymentProcessingService::class)->refund($order, 'ref_123', amount: 1000);

    Event::assertDispatched(
        PaymentFailed::class,
        fn (PaymentFailed $event): bool => $event->order->is($order)
            && $event->type === TransactionType::Refund
            && $event->reason === 'insufficient_funds'
    );
});
