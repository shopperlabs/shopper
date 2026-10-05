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
