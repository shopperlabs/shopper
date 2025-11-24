<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Livewire\Pages\Settings\Payment;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe(Payment::class, function (): void {
    it('can render payment settings component', function (): void {
        Livewire::test(Payment::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.settings.payment');
    });

    it('initializes tabs on mount', function (): void {
        $component = Livewire::test(Payment::class);

        expect($component->get('tabs'))->toBeArray()
            ->and($component->get('tabs'))->toContain('general');
    });

    it('can list payment methods in table', function (): void {
        PaymentMethod::factory()->count(3)->create();

        Livewire::test(Payment::class)
            ->assertCanSeeTableRecords(PaymentMethod::limit(3)->get());
    });
})->group('livewire', 'settings', 'payment');
