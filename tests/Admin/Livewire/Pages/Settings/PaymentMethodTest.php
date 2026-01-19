<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\PaymentMethod as PaymentMethodModel;
use Shopper\Livewire\Pages\Settings\PaymentMethod;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe(PaymentMethod::class, function (): void {
    it('can render payment settings component', function (): void {
        Livewire::test(PaymentMethod::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.settings.payment-method');
    });

    it('initializes tabs on mount', function (): void {
        $component = Livewire::test(PaymentMethod::class);

        expect($component->get('tabs'))->toBeArray()
            ->and($component->get('tabs'))->toContain('general');
    });

    it('can list payment methods in table', function (): void {
        PaymentMethodModel::factory()->count(3)->create();

        Livewire::test(PaymentMethod::class)
            ->loadTable()
            ->assertCanSeeTableRecords(PaymentMethodModel::limit(3)->get());
    });

    it('can create new payment method via action', function (): void {
        $initialCount = PaymentMethodModel::query()->count();

        Livewire::test(PaymentMethod::class)
            ->callAction('createPayment', [
                'title' => 'Stripe',
                'link_url' => 'https://stripe.com',
                'description' => 'Pay with credit card',
                'instructions' => 'Enter your card details',
            ])
            ->assertHasNoActionErrors()
            ->assertNotified(__('shopper::notifications.payment.add'));

        expect(PaymentMethodModel::query()->count())->toBe($initialCount + 1)
            ->and(PaymentMethodModel::query()->where('title', 'Stripe')->exists())->toBeTrue();
    });

    it('validates required title when creating payment method', function (): void {
        Livewire::test(PaymentMethod::class)
            ->callAction('createPayment', [
                'title' => '',
            ])
            ->assertHasActionErrors(['title' => 'required']);
    });

    it('validates url format for link_url when creating payment method', function (): void {
        Livewire::test(PaymentMethod::class)
            ->callAction('createPayment', [
                'title' => 'Test Payment',
                'link_url' => 'not-a-valid-url',
            ])
            ->assertHasActionErrors(['link_url' => 'url']);
    });

    it('can edit payment method via table action', function (): void {
        $payment = PaymentMethodModel::factory()->create([
            'title' => 'Old Title',
        ]);

        Livewire::test(PaymentMethod::class)
            ->callTableAction('edit', $payment, [
                'title' => 'New Title',
                'link_url' => 'https://example.com',
            ])
            ->assertHasNoTableActionErrors();

        $payment->refresh();

        expect($payment->title)->toBe('New Title');
    });

    it('can delete payment method via table action', function (): void {
        $payment = PaymentMethodModel::factory()->create();

        Livewire::test(PaymentMethod::class)
            ->callTableAction('delete', $payment);

        expect(PaymentMethodModel::query()->find($payment->id))->toBeNull();
    });
})->group('livewire', 'settings', 'payment');
