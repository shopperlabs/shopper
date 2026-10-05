<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Exceptions;
use Shopper\Core\Contracts\PaymentSessionGateway;
use Shopper\Core\Exceptions\PaymentProviderUnavailableException;
use Shopper\Core\Models\Order;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Facades\Payment;
use Shopper\Payment\Models\PaymentTransaction;
use Tests\Api\Stubs\FakePaymentDriver;

uses(Tests\Core\TestCase::class);

beforeEach(function (): void {
    $this->driver = $driver = new FakePaymentDriver;
    Payment::extend('fake', fn (): FakePaymentDriver => $driver);

    $this->gateway = resolve(PaymentSessionGateway::class);
    $this->session = ['driver' => 'fake', 'reference' => 'pi_1', 'amount' => 1000];
});

it('never asks the provider about a manual session', function (): void {
    $session = ['driver' => 'manual', 'reference' => 'manual_1'];

    expect($this->gateway->collected($session))->toBeFalse()
        ->and($this->gateway->release($session))->toBeTrue()
        ->and($this->driver->retrievals + $this->driver->cancellations)->toBe(0);
});

it('releases an unpaid session by cancelling its intent', function (): void {
    expect($this->gateway->release($this->session))->toBeTrue()
        ->and($this->driver->lastCancelledReference)->toBe('pi_1');
});

it('refuses to release a session the provider collected, without cancelling it', function (string $status): void {
    $this->driver->retrievedStatus = $status;

    expect($this->gateway->release($this->session))->toBeFalse()
        ->and($this->driver->cancellations)->toBe(0);
})->with(['authorized', 'captured', 'processing']);

it('refuses to release a session paid between the check and the cancellation', function (): void {
    $this->driver->retrievedStatuses = ['pending', 'captured'];
    $this->driver->throwOnCancel = true;

    expect($this->gateway->release($this->session))->toBeFalse();
});

it('releases a session the provider can no longer collect once the cancellation failed', function (string $status): void {
    $this->driver->retrievedStatuses = [$status, $status];
    $this->driver->throwOnCancel = true;

    expect($this->gateway->release($this->session))->toBeTrue();
})->with(['canceled', 'failed', 'refunded']);

it('fails when the provider cannot tell the state of a session it refused to cancel', function (): void {
    $this->driver->retrievedStatuses = ['pending', 'pending'];
    $this->driver->throwOnCancel = true;

    $this->gateway->release($this->session);
})->throws(PaymentProviderUnavailableException::class);

it('fails when the provider cannot be reached', function (): void {
    $this->driver->throwOnRetrieve = true;

    $this->gateway->release($this->session);
})->throws(PaymentProviderUnavailableException::class);

it('treats a session of a driver without retrieval as possibly paid', function (): void {
    $this->driver->retrieval = false;

    expect($this->gateway->collected($this->session))->toBeNull()
        ->and($this->gateway->release($this->session))->toBeFalse()
        ->and($this->driver->cancellations)->toBe(0);
});

it('refunds the amount the provider captured rather than the amount the session asked', function (): void {
    $this->driver->retrievedStatus = 'captured';
    $this->driver->lastAmount = 700;

    $this->gateway->refund($this->session);

    expect($this->driver->refunds[0]['amount'])->toBe(700);
});

it('refunds a captured session once, whatever the number of attempts', function (): void {
    $this->driver->retrievedStatus = 'captured';

    expect($this->gateway->refund($this->session))->toBeTrue()
        ->and($this->driver->refunds)->toHaveCount(1)
        ->and($this->driver->refunds[0]['amount'])->toBe(1000)
        ->and($this->driver->refunds[0]['context'])->toBe(['idempotency_key' => 'refund-pi_1'])
        ->and($this->driver->cancellations)->toBe(0);
});

it('voids an authorized session instead of refunding it', function (): void {
    $this->driver->retrievedStatus = 'authorized';

    expect($this->gateway->refund($this->session))->toBeTrue()
        ->and($this->driver->lastCancelledReference)->toBe('pi_1')
        ->and($this->driver->refunds)->toBeEmpty();
});

it('has nothing to give back on a session the provider never collected or already gave back', function (string $status): void {
    $this->driver->retrievedStatus = $status;

    expect($this->gateway->refund($this->session))->toBeTrue()
        ->and($this->driver->refunds)->toBeEmpty()
        ->and($this->driver->cancellations)->toBe(0);
})->with(['pending', 'requires_action', 'canceled', 'failed', 'refunded']);

it('cannot give back a payment still processing or held by a driver without retrieval', function (): void {
    $this->driver->retrievedStatus = 'processing';

    expect($this->gateway->refund($this->session))->toBeFalse();

    $this->driver->retrieval = false;

    expect($this->gateway->refund($this->session))->toBeFalse()
        ->and($this->driver->refunds)->toBeEmpty();
});

it('does not count as given back a payment in a status outside the shared vocabulary', function (): void {
    $this->driver->retrievedStatus = 'succeeded';

    expect($this->gateway->refund($this->session))->toBeFalse()
        ->and($this->driver->refunds)->toBeEmpty();
});

function initiatedPayment(Order $order, string $reference = 'pi_order'): void
{
    PaymentTransaction::factory()->create([
        'order_id' => $order->id,
        'driver' => 'fake',
        'type' => TransactionType::Initiate,
        'reference' => $reference,
    ]);
}

describe('releaseOrder', function (): void {
    beforeEach(function (): void {
        $this->order = Order::factory()->create();
    });

    it('releases an order without a provider payment', function (): void {
        expect($this->gateway->releaseOrder($this->order))->toBeTrue()
            ->and($this->driver->retrievals)->toBe(0);
    });

    it('cancels the latest intent of an unpaid order', function (): void {
        initiatedPayment($this->order, 'pi_old');
        initiatedPayment($this->order, 'pi_latest');

        expect($this->gateway->releaseOrder($this->order))->toBeTrue()
            ->and($this->driver->lastCancelledReference)->toBe('pi_latest');
    });

    it('keeps an order whose payment was collected or is processing', function (string $status): void {
        initiatedPayment($this->order);
        $this->driver->retrievedStatus = $status;

        expect($this->gateway->releaseOrder($this->order))->toBeFalse()
            ->and($this->driver->cancellations)->toBe(0);
    })->with(['authorized', 'captured', 'processing']);

    it('releases the order of a driver without retrieval only once its intent is cancelled', function (): void {
        initiatedPayment($this->order);
        $this->driver->retrieval = false;

        expect($this->gateway->releaseOrder($this->order))->toBeTrue()
            ->and($this->driver->cancellations)->toBe(1);

        Exceptions::fake();
        $this->driver->throwOnCancel = true;

        expect($this->gateway->releaseOrder($this->order))->toBeFalse();
        Exceptions::assertReportedCount(1);
    });
});
