<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Zone;
use Shopper\Livewire\SlideOvers\ShippingOptionForm;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('access_setting');
    $this->actingAs($this->user);

    $currency = Currency::query()->where('code', 'USD')->first();
    $this->zone = Zone::factory()->create(['currency_id' => $currency->id]);
    $this->carrier = Carrier::factory()->create();
    $this->zone->carriers()->attach($this->carrier->id);
});

describe(ShippingOptionForm::class, function (): void {
    it('can render shipping option form for creating new option', function (): void {
        Livewire::test(ShippingOptionForm::class, ['zoneId' => $this->zone->id])
            ->assertOk();
    });

    it('can render shipping option form for editing existing option', function (): void {
        $option = CarrierOption::factory()->create([
            'zone_id' => $this->zone->id,
            'carrier_id' => $this->carrier->id,
        ]);

        Livewire::test(ShippingOptionForm::class, [
            'zoneId' => $this->zone->id,
            'optionId' => $option->id,
        ])
            ->assertOk();
    });

    it('loads zone with currency and carriers', function (): void {
        $component = Livewire::test(ShippingOptionForm::class, ['zoneId' => $this->zone->id]);

        expect($component->get('zone'))->not->toBeNull()
            ->and($component->get('zone')->id)->toBe($this->zone->id)
            ->and($component->get('zone')->currency)->not->toBeNull()
            ->and($component->get('zone')->carriers)->not->toBeEmpty();
    });

    it('loads option data when editing', function (): void {
        $option = CarrierOption::factory()->create([
            'zone_id' => $this->zone->id,
            'carrier_id' => $this->carrier->id,
            'name' => 'Express Shipping',
            'price' => 15.99,
        ]);

        $component = Livewire::test(ShippingOptionForm::class, [
            'zoneId' => $this->zone->id,
            'optionId' => $option->id,
        ]);

        expect($component->get('option'))->not->toBeNull()
            ->and($component->get('option')->id)->toBe($option->id)
            ->and($component->get('option')->name)->toBe('Express Shipping');
    });

    it('sets correct title for new option', function (): void {
        $component = Livewire::test(ShippingOptionForm::class, ['zoneId' => $this->zone->id]);

        expect($component->get('title'))->toContain($this->zone->name);
    });

    it('sets correct title for editing option', function (): void {
        $option = CarrierOption::factory()->create([
            'zone_id' => $this->zone->id,
            'carrier_id' => $this->carrier->id,
            'name' => 'Standard Shipping',
        ]);

        $component = Livewire::test(ShippingOptionForm::class, [
            'zoneId' => $this->zone->id,
            'optionId' => $option->id,
        ]);

        expect($component->get('title'))->toContain('Standard Shipping');
    });

    it('can create new shipping option', function (): void {
        Livewire::test(ShippingOptionForm::class, ['zoneId' => $this->zone->id])
            ->fillForm([
                'name' => 'Standard Delivery',
                'price' => 10,
                'carrier_id' => $this->carrier->id,
                'description' => 'Delivery within 3-5 business days',
                'is_enabled' => true,
            ])
            ->call('store')
            ->assertHasNoFormErrors();

        $option = CarrierOption::query()->where('name', 'Standard Delivery')->first();

        expect($option)->not->toBeNull()
            ->and($option->zone_id)->toBe($this->zone->id)
            ->and($option->carrier_id)->toBe($this->carrier->id)
            ->and($option->price)->toBe(10)
            ->and($option->description)->toBe('Delivery within 3-5 business days')
            ->and($option->is_enabled)->toBeTrue();
    });

    it('can update existing shipping option', function (): void {
        $option = CarrierOption::factory()->create([
            'zone_id' => $this->zone->id,
            'carrier_id' => $this->carrier->id,
            'name' => 'Old Name',
            'price' => 1000,
        ]);

        Livewire::test(ShippingOptionForm::class, [
            'zoneId' => $this->zone->id,
            'optionId' => $option->id,
        ])
            ->fillForm([
                'name' => 'Updated Name',
                'price' => 15,
                'carrier_id' => $this->carrier->id,
            ])
            ->call('store')
            ->assertHasNoFormErrors();

        $option->refresh();

        expect($option->name)->toBe('Updated Name')
            ->and($option->price)->toBe(15);
    });

    it('validates required fields', function (): void {
        Livewire::test(ShippingOptionForm::class, ['zoneId' => $this->zone->id])
            ->fillForm([
                'name' => '',
                'price' => '',
                'carrier_id' => null,
            ])
            ->call('store')
            ->assertHasFormErrors([
                'name' => 'required',
                'price' => 'required',
                'carrier_id' => 'required',
            ]);
    });

    it('validates price is numeric', function (): void {
        Livewire::test(ShippingOptionForm::class, ['zoneId' => $this->zone->id])
            ->fillForm([
                'name' => 'Test Option',
                'price' => 'not-a-number',
                'carrier_id' => $this->carrier->id,
            ])
            ->call('store')
            ->assertHasFormErrors(['price']);
    });

    it('validates description max length', function (): void {
        Livewire::test(ShippingOptionForm::class, ['zoneId' => $this->zone->id])
            ->fillForm([
                'name' => 'Test Option',
                'price' => 10,
                'carrier_id' => $this->carrier->id,
                'description' => str_repeat('a', 201),
            ])
            ->call('store')
            ->assertHasFormErrors(['description']);
    });

    it('can save metadata', function (): void {
        Livewire::test(ShippingOptionForm::class, ['zoneId' => $this->zone->id])
            ->fillForm([
                'name' => 'Option with Metadata',
                'price' => 12,
                'carrier_id' => $this->carrier->id,
                'metadata' => [
                    'tracking' => 'enabled',
                    'insurance' => 'optional',
                ],
            ])
            ->call('store')
            ->assertHasNoFormErrors();

        $option = CarrierOption::query()->where('name', 'Option with Metadata')->first();

        expect($option->metadata)->toBe([
            'tracking' => 'enabled',
            'insurance' => 'optional',
        ]);
    });

    it('dispatches zone changed event after saving', function (): void {
        Livewire::test(ShippingOptionForm::class, ['zoneId' => $this->zone->id])
            ->fillForm([
                'name' => 'Test Option',
                'price' => 10,
                'carrier_id' => $this->carrier->id,
            ])
            ->call('store')
            ->assertDispatched('zone.changed', currentZoneId: $this->zone->id);
    });

    it('displays currency code in price field', function (): void {
        $component = Livewire::test(ShippingOptionForm::class, ['zoneId' => $this->zone->id]);

        expect($component->get('zone')->currency->code)->toBe('USD');
    });

    it('only loads carriers associated with the zone', function (): void {
        $otherCarrier = Carrier::factory()->create(['name' => 'Other Carrier']);

        $component = Livewire::test(ShippingOptionForm::class, ['zoneId' => $this->zone->id]);

        expect($component->get('zone')->carriers->pluck('id')->toArray())
            ->toContain($this->carrier->id)
            ->not->toContain($otherCarrier->id);
    });
})->group('livewire', 'slideovers', 'settings');
