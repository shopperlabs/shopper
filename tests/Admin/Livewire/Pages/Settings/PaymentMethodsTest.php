<?php

declare(strict_types=1);

use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Livewire\Pages\Order\Detail;
use Shopper\Livewire\Pages\Settings\PaymentMethods;
use Shopper\Payment\Enum\TransactionStatus;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Models\PaymentTransaction;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('system.settings');
    $this->actingAs($this->user);
});

describe(PaymentMethods::class, function (): void {
    it('can render payment settings component', function (): void {
        Livewire::test(PaymentMethods::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.settings.payment-methods');
    });

    it('can list payment methods in table', function (): void {
        PaymentMethod::factory()->count(3)->create();

        Livewire::test(PaymentMethods::class)
            ->loadTable()
            ->assertCanSeeTableRecords(PaymentMethod::limit(3)->get());
    });

    it('can create new payment method via action', function (): void {
        $initialCount = PaymentMethod::query()->count();

        Livewire::test(PaymentMethods::class)
            ->callAction('createPayment', [
                'title' => 'Stripe',
                'link_url' => 'https://stripe.com',
                'description' => 'Pay with credit card',
                'instructions' => 'Enter your card details',
            ])
            ->assertHasNoErrors()
            ->assertNotified(__('shopper::notifications.payment.add'));

        expect(PaymentMethod::query()->count())->toBe($initialCount + 1)
            ->and(PaymentMethod::query()->where('title', 'Stripe')->exists())->toBeTrue();
    });

    it('validates required title when creating payment method', function (): void {
        Livewire::test(PaymentMethods::class)
            ->callAction('createPayment', [
                'title' => '',
            ])
            ->assertHasFormErrors(['title' => 'required']);
    });

    it('validates url format for link_url when creating payment method', function (): void {
        Livewire::test(PaymentMethods::class)
            ->callAction('createPayment', [
                'title' => 'Test Payment',
                'link_url' => 'not-a-valid-url',
            ])
            ->assertHasFormErrors(['link_url' => 'url']);
    });

    it('can edit payment method via table action', function (): void {
        $payment = PaymentMethod::factory()->create([
            'title' => 'Old Title',
        ]);

        Livewire::test(PaymentMethods::class)
            ->callAction(TestAction::make('edit')->table($payment), [
                'title' => 'New Title',
                'driver' => 'manual',
                'link_url' => 'https://example.com',
            ])
            ->assertHasNoFormErrors();

        $payment->refresh();

        expect($payment->title)->toBe('New Title');
    });

    it('can delete payment method via table action', function (): void {
        $payment = PaymentMethod::factory()->create();

        Livewire::test(PaymentMethods::class)
            ->callAction(TestAction::make('delete')->table($payment));

        expect(PaymentMethod::query()->find($payment->id))->toBeNull();
    });

    it('refuses to delete a payment method an order uses', function (bool $deleted): void {
        $payment = PaymentMethod::factory()->create();
        $order = Order::factory()->create(['payment_method_id' => $payment->id]);

        if ($deleted) {
            $order->delete();
        }

        Livewire::test(PaymentMethods::class)
            ->assertActionDisabled(TestAction::make('delete')->table($payment))
            ->callAction(TestAction::make('delete')->table($payment));

        expect(PaymentMethod::query()->find($payment->id))->not->toBeNull()
            ->and($order->fresh()->payment_method_id)->toBe($payment->id);
    })->with(['placed' => false, 'soft deleted' => true]);

    it('refuses to delete a payment method a checkout in progress holds a payment session on', function (): void {
        $payment = PaymentMethod::factory()->create();
        Cart::factory()->create([
            'payment_method_id' => $payment->id,
            'payment_session' => ['driver' => 'stripe', 'reference' => 'pi_123'],
        ]);

        Livewire::test(PaymentMethods::class)
            ->assertActionDisabled(TestAction::make('delete')->table($payment))
            ->callAction(TestAction::make('delete')->table($payment));

        expect(PaymentMethod::query()->find($payment->id))->not->toBeNull();
    });

    it('deletes a payment method a cart only selected or a completed checkout held', function (): void {
        $payment = PaymentMethod::factory()->create();
        Cart::factory()->create(['payment_method_id' => $payment->id]);
        Cart::factory()->create([
            'payment_method_id' => $payment->id,
            'payment_session' => ['driver' => 'stripe', 'reference' => 'pi_123'],
            'completed_at' => now(),
        ]);

        Livewire::test(PaymentMethods::class)
            ->assertActionEnabled(TestAction::make('delete')->table($payment))
            ->callAction(TestAction::make('delete')->table($payment));

        expect(PaymentMethod::query()->find($payment->id))->toBeNull();
    });

    it('keeps the driver of a payment method an order or a checkout in progress relies on', function (): void {
        $ordered = PaymentMethod::factory()->create(['driver' => 'stripe']);
        Order::factory()->create(['payment_method_id' => $ordered->id]);
        $inCheckout = PaymentMethod::factory()->create(['driver' => 'stripe']);
        Cart::factory()->create([
            'payment_method_id' => $inCheckout->id,
            'payment_session' => ['driver' => 'stripe', 'reference' => 'pi_123'],
        ]);
        $unused = PaymentMethod::factory()->create(['driver' => 'stripe']);

        $component = Livewire::test(PaymentMethods::class);

        foreach ([$ordered, $inCheckout, $unused] as $payment) {
            $component
                ->callAction(TestAction::make('edit')->table($payment), ['title' => $payment->title, 'driver' => 'manual'])
                ->assertHasNoFormErrors();
        }

        expect($ordered->fresh()->driver)->toBe('stripe')
            ->and($inCheckout->fresh()->driver)->toBe('stripe')
            ->and($unused->fresh()->driver)->toBe('manual');
    });

    it('edits a payment method an order uses although its driver was never set', function (): void {
        $payment = PaymentMethod::factory()->create(['driver' => null]);
        Order::factory()->create(['payment_method_id' => $payment->id]);

        Livewire::test(PaymentMethods::class)
            ->callAction(TestAction::make('edit')->table($payment), ['title' => 'Cash on pickup'])
            ->assertHasNoFormErrors();

        expect($payment->fresh())
            ->title->toBe('Cash on pickup')
            ->driver->toBeNull();
    });

    it('keeps an authorized order capturable after a refused delete of its payment method', function (): void {
        $this->user->givePermissionTo(['orders.read', 'orders.edit']);

        $payment = PaymentMethod::factory()->create(['driver' => 'manual']);
        $order = Order::factory()->hasItems(1)->create([
            'status' => OrderStatus::New,
            'payment_status' => PaymentStatus::Authorized,
            'payment_method_id' => $payment->id,
            'price_amount' => 5000,
            'currency_code' => 'USD',
        ]);
        PaymentTransaction::factory()->create([
            'order_id' => $order->id,
            'payment_method_id' => $payment->id,
            'type' => TransactionType::Authorize,
            'status' => TransactionStatus::Success,
            'reference' => 'manual_123',
            'amount' => 5000,
        ]);

        Livewire::test(PaymentMethods::class)
            ->callAction(TestAction::make('delete')->table($payment));

        Livewire::test(Detail::class, ['order' => $order->fresh()])
            ->assertActionVisible('capturePayment')
            ->callAction('capturePayment')
            ->assertNotified(__('shopper::pages/orders.notifications.captured'));

        expect($order->fresh()->payment_status)->toBe(PaymentStatus::Paid)
            ->and(PaymentTransaction::query()->where('type', TransactionType::Capture)->sole()->payment_method_id)->toBe($payment->id);
    });

    it('forbids mount for users without `system.settings`', function (): void {
        $unauthorized = User::factory()->create();
        $this->actingAs($unauthorized);

        Livewire::test(PaymentMethods::class)
            ->assertForbidden();
    });
})->group('livewire', 'settings', 'payment');
