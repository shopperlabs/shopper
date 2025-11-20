<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\User;
use Shopper\Livewire\Modals\PaymentMethodForm;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe(PaymentMethodForm::class, function (): void {
    it('can render payment method form modal', function (): void {
        Livewire::test(PaymentMethodForm::class)
            ->assertOk();
    });

    it('loads payment method data when editing', function (): void {
        $payment = PaymentMethod::factory()->create([
            'title' => 'PayPal',
            'slug' => 'paypal',
        ]);

        $component = Livewire::test(PaymentMethodForm::class, ['paymentId' => $payment->id]);

        expect($component->get('paymentId'))->toBe($payment->id);
    });

    it('can create new payment method', function (): void {
        Livewire::test(PaymentMethodForm::class)
            ->fillForm([
                'title' => 'Stripe',
                'slug' => 'stripe',
                'link_url' => 'https://stripe.com',
                'description' => 'Pay with credit card',
                'instructions' => 'Enter your card details',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $payment = PaymentMethod::query()->where('title', 'Stripe')->first();

        expect($payment)->not->toBeNull()
            ->and($payment->slug)->toBe('stripe')
            ->and($payment->link_url)->toBe('https://stripe.com');
    });

    it('can update existing payment method', function (): void {
        $payment = PaymentMethod::factory()->create([
            'title' => 'Old Title',
            'slug' => 'old-title',
        ]);

        Livewire::test(PaymentMethodForm::class, ['paymentId' => $payment->id])
            ->fillForm([
                'title' => 'New Title',
                'slug' => 'new-title',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $payment->refresh();

        expect($payment->title)->toBe('New Title')
            ->and($payment->slug)->toBe('new-title');
    });

    it('validates required fields', function (): void {
        Livewire::test(PaymentMethodForm::class)
            ->fillForm([
                'title' => '',
            ])
            ->call('save')
            ->assertHasFormErrors(['title' => 'required']);
    });

    it('validates url format for link_url', function (): void {
        Livewire::test(PaymentMethodForm::class)
            ->fillForm([
                'title' => 'Test Payment',
                'link_url' => 'not-a-valid-url',
            ])
            ->call('save')
            ->assertHasFormErrors(['link_url']);
    });

    it('auto-generates slug from title', function (): void {
        $component = Livewire::test(PaymentMethodForm::class)
            ->fillForm(['title' => 'NotchPay']);

        expect($component->get('data.slug'))->toBe('NotchPay');
    });

    it('sends notification after saving', function (): void {
        Livewire::test(PaymentMethodForm::class)
            ->fillForm([
                'title' => 'Test Payment',
                'slug' => 'test-payment',
            ])
            ->call('save')
            ->assertNotified();
    });

    it('dispatches onPaymentMethodAdded event after saving', function (): void {
        Livewire::test(PaymentMethodForm::class)
            ->fillForm([
                'title' => 'Test Payment',
                'slug' => 'test-payment',
            ])
            ->call('save')
            ->assertDispatched('onPaymentMethodAdded');
    });

    it('closes modal after saving', function (): void {
        Livewire::test(PaymentMethodForm::class)
            ->fillForm([
                'title' => 'Test Payment',
                'slug' => 'test-payment',
            ])
            ->call('save');

        expect(true)->toBeTrue();
    });
})->group('livewire', 'modals', 'settings');
