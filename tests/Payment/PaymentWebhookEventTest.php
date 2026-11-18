<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Shopper\Payment\DataTransferObjects\WebhookResult;
use Shopper\Payment\Enum\WebhookAction;
use Shopper\Payment\Models\PaymentWebhookEvent;

uses(Tests\Core\TestCase::class);

it('claims a webhook event exactly once', function (): void {
    $event = PaymentWebhookEvent::factory()->create();

    expect($event->claim())->toBeTrue()
        ->and($event->claim())->toBeFalse()
        ->and($event->refresh()->isProcessed())->toBeTrue();
});

it('counts the refunds of a payment without those that failed', function (array $events, int $refunded, bool $returned): void {
    $journal = Collection::make($events)->map(fn (array $event): PaymentWebhookEvent => PaymentWebhookEvent::factory()
        ->fromResult(new WebhookResult(
            action: $event[0],
            reference: 'pi_1',
            amount: $event[1],
            data: array_filter(['refund_id' => $event[2]]),
        ))
        ->make());

    expect(PaymentWebhookEvent::refundedAmount($journal))->toBe($refunded)
        ->and(PaymentWebhookEvent::returnsPayment($journal))->toBe($returned);
})->with([
    'a refund that failed' => [[[WebhookAction::Refunded, 3200, 're_a'], [WebhookAction::RefundFailed, 3200, 're_a']], 0, false],
    'a failure journaled before its refund' => [[[WebhookAction::RefundFailed, 3200, 're_a'], [WebhookAction::Refunded, 3200, 're_a']], 0, false],
    'one refund out of two failed' => [[[WebhookAction::Refunded, 1600, 're_a'], [WebhookAction::Refunded, 1600, 're_b'], [WebhookAction::RefundFailed, 1600, 're_a']], 1600, true],
    'refunded again after a failure' => [[[WebhookAction::Refunded, 3200, 're_a'], [WebhookAction::RefundFailed, 3200, 're_a'], [WebhookAction::Refunded, 3200, 're_b']], 3200, true],
    'the failure of another refund' => [[[WebhookAction::Refunded, 3200, 're_a'], [WebhookAction::RefundFailed, 3200, 're_b']], 3200, true],
    'a redelivered refund' => [[[WebhookAction::Refunded, 3200, 're_a'], [WebhookAction::Refunded, 3200, 're_a']], 3200, true],
    'a failure that names no refund' => [[[WebhookAction::Refunded, 3200, null], [WebhookAction::RefundFailed, 3200, null]], 3200, true],
    'a refund without an amount' => [[[WebhookAction::Refunded, null, 're_a']], 0, true],
    'a cancellation' => [[[WebhookAction::Canceled, null, null]], 0, true],
    'a capture alone' => [[[WebhookAction::Captured, 3200, null]], 0, false],
]);
