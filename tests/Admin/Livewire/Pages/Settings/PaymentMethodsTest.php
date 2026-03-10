<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Livewire\Pages\Settings\PaymentMethods;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('access_setting');
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
            ->assertHasNoActionErrors()
            ->assertNotified(__('shopper::notifications.payment.add'));

        expect(PaymentMethod::query()->count())->toBe($initialCount + 1)
            ->and(PaymentMethod::query()->where('title', 'Stripe')->exists())->toBeTrue();
    });

    it('validates required title when creating payment method', function (): void {
        Livewire::test(PaymentMethods::class)
            ->callAction('createPayment', [
                'title' => '',
            ])
            ->assertHasActionErrors(['title' => 'required']);
    });

    it('validates url format for link_url when creating payment method', function (): void {
        Livewire::test(PaymentMethods::class)
            ->callAction('createPayment', [
                'title' => 'Test Payment',
                'link_url' => 'not-a-valid-url',
            ])
            ->assertHasActionErrors(['link_url' => 'url']);
    });

    it('can edit payment method via table action', function (): void {
        $payment = PaymentMethod::factory()->create([
            'title' => 'Old Title',
        ]);

        Livewire::test(PaymentMethods::class)
            ->callTableAction('edit', $payment, [
                'title' => 'New Title',
                'driver' => 'manual',
                'link_url' => 'https://example.com',
            ])
            ->assertHasNoTableActionErrors();

        $payment->refresh();

        expect($payment->title)->toBe('New Title');
    });

    it('can delete payment method via table action', function (): void {
        $payment = PaymentMethod::factory()->create();

        Livewire::test(PaymentMethods::class)
            ->callTableAction('delete', $payment);

        expect(PaymentMethod::query()->find($payment->id))->toBeNull();
    });
})->group('livewire', 'settings', 'payment');
