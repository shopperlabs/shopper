<?php

declare(strict_types=1);

use Shopper\Payment\DataTransferObjects\PaymentResult;

it('counts a payment as collected once the provider holds or moves the money', function (string $status, bool $collected): void {
    expect((new PaymentResult(success: true, status: $status))->isCollected())->toBe($collected);
})->with([
    ['authorized', true],
    ['captured', true],
    ['processing', true],
    ['pending', false],
    ['requires_action', false],
    ['canceled', false],
]);

it('reports a result as pending only when it succeeded without being settled', function (bool $success, string $status, bool $pending): void {
    expect((new PaymentResult(success: $success, status: $status))->isPending())->toBe($pending);
})->with([
    [true, 'pending', true],
    [false, 'pending', false],
    [true, 'refunded', false],
    [true, 'requires_action', true],
]);

it('builds a pending refund under the provider refund id', function (): void {
    $result = PaymentResult::pendingRefund('rf_1', 2000, ['provider' => 'deferred']);

    expect($result->isPending())->toBeTrue()
        ->and($result->reference)->toBe('rf_1')
        ->and($result->amount)->toBe(2000)
        ->and($result->data)->toBe(['provider' => 'deferred']);
});
