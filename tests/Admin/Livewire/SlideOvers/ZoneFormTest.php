<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\Zone;
use Shopper\Livewire\SlideOvers\ZoneForm;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('access_setting');
    $this->actingAs($this->user);
});

describe(ZoneForm::class, function (): void {
    it('can render zone form for creating new zone', function (): void {
        Livewire::test(ZoneForm::class)
            ->assertOk();
    });

    it('can render zone form for editing existing zone', function (): void {
        $zone = Zone::factory()->create();

        Livewire::test(ZoneForm::class, ['zoneId' => $zone->id])
            ->assertOk();
    });

    it('loads zone data when editing', function (): void {
        $zone = Zone::factory()->create(['name' => 'Europe Zone']);

        $component = Livewire::test(ZoneForm::class, ['zoneId' => $zone->id]);

        expect($component->get('zone'))->not->toBeNull()
            ->and($component->get('zone')->id)->toBe($zone->id)
            ->and($component->get('zone')->name)->toBe('Europe Zone');
    });

    it('can create new zone', function (): void {
        $country = Country::query()->first();
        $currency = Currency::query()->where('code', 'USD')->first();
        $payment = PaymentMethod::factory()->create();
        $carrier = Carrier::factory()->create();

        Livewire::test(ZoneForm::class)
            ->fillForm([
                'name' => 'Africa Zone',
                'code' => 'AF',
                'countries' => [$country->id],
                'currency_id' => $currency->id,
                'payments' => [$payment->id],
                'carriers' => [$carrier->id],
                'is_enabled' => true,
            ])
            ->call('store')
            ->assertHasNoFormErrors();

        $zone = Zone::query()->where('name', 'Africa Zone')->first();

        expect($zone)->not->toBeNull()
            ->and($zone->code)->toBe('AF')
            ->and($zone->is_enabled)->toBeTrue()
            ->and($zone->countries->count())->toBe(1)
            ->and($zone->paymentMethods->count())->toBe(1)
            ->and($zone->carriers->count())->toBe(1);
    });

    it('generates slug from name', function (): void {
        $country = Country::query()->first();
        $currency = Currency::query()->where('code', 'USD')->first();
        $payment = PaymentMethod::factory()->create();
        $carrier = Carrier::factory()->create();

        Livewire::test(ZoneForm::class)
            ->fillForm([
                'name' => 'North America',
                'countries' => [$country->id],
                'currency_id' => $currency->id,
                'payments' => [$payment->id],
                'carriers' => [$carrier->id],
            ])
            ->call('store');

        $zone = Zone::query()->where('name', 'North America')->first();

        expect($zone->slug)->toBe('north-america');
    });

    it('can update existing zone', function (): void {
        $zone = Zone::factory()->create(['name' => 'Old Name']);
        $country = Country::query()->first();
        $currency = Currency::query()->where('code', 'USD')->first();
        $payment = PaymentMethod::factory()->create();
        $carrier = Carrier::factory()->create();

        Livewire::test(ZoneForm::class, ['zoneId' => $zone->id])
            ->fillForm([
                'name' => 'Updated Name',
                'code' => 'UP',
                'countries' => [$country->id],
                'currency_id' => $currency->id,
                'payments' => [$payment->id],
                'carriers' => [$carrier->id],
            ])
            ->call('store')
            ->assertHasNoFormErrors();

        $zone->refresh();

        expect($zone->name)->toBe('Updated Name')
            ->and($zone->code)->toBe('UP');
    });

    it('validates required fields', function (): void {
        Livewire::test(ZoneForm::class)
            ->fillForm([
                'name' => '',
            ])
            ->call('store')
            ->assertHasFormErrors(['name' => 'required']);
    });

    it('redirects after successful creation', function (): void {
        $country = Country::query()->first();
        $currency = Currency::query()->where('code', 'USD')->first();
        $payment = PaymentMethod::factory()->create();
        $carrier = Carrier::factory()->create();

        Livewire::test(ZoneForm::class)
            ->fillForm([
                'name' => 'Test Zone',
                'countries' => [$country->id],
                'currency_id' => $currency->id,
                'payments' => [$payment->id],
                'carriers' => [$carrier->id],
            ])
            ->call('store')
            ->assertRedirect();
    });
})->group('livewire', 'slideovers', 'settings');
