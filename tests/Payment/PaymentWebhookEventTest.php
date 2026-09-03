<?php

declare(strict_types=1);

use Shopper\Payment\Models\PaymentWebhookEvent;

uses(Tests\Core\TestCase::class);

it('claims a webhook event exactly once', function (): void {
    $event = PaymentWebhookEvent::factory()->create();

    expect($event->claim())->toBeTrue()
        ->and($event->claim())->toBeFalse()
        ->and($event->refresh()->isProcessed())->toBeTrue();
});
