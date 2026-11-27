<?php

declare(strict_types=1);

use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\OrderShipping;
use Shopper\Livewire\Pages\Settings\Carriers;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('system.settings');
    $this->actingAs($this->user);
});

describe(Carriers::class, function (): void {
    it('refuses to delete a carrier that has shipping options', function (): void {
        $carrier = Carrier::factory()->create();
        CarrierOption::factory()->create(['carrier_id' => $carrier->id]);

        Livewire::test(Carriers::class)
            ->assertActionDisabled(TestAction::make('delete')->table($carrier))
            ->callAction(TestAction::make('delete')->table($carrier));

        expect(Carrier::query()->find($carrier->id))->not->toBeNull();
    });

    it('refuses to delete a carrier that shipped orders', function (): void {
        $carrier = Carrier::factory()->create();
        $shipment = OrderShipping::factory()->create(['carrier_id' => $carrier->id]);

        Livewire::test(Carriers::class)
            ->assertActionDisabled(TestAction::make('delete')->table($carrier))
            ->callAction(TestAction::make('delete')->table($carrier));

        expect(Carrier::query()->find($carrier->id))->not->toBeNull()
            ->and($shipment->fresh()->carrier_id)->toBe($carrier->id);
    });

    it('keeps the driver of a carrier whose shipments are tracked through it', function (): void {
        $shipped = Carrier::factory()->create(['driver' => 'ups']);
        OrderShipping::factory()->create(['carrier_id' => $shipped->id]);
        $unused = Carrier::factory()->create(['driver' => 'ups']);

        Livewire::test(Carriers::class)
            ->callAction(TestAction::make('edit')->table($shipped), ['name' => $shipped->name, 'driver' => 'manual'])
            ->assertHasNoFormErrors()
            ->callAction(TestAction::make('edit')->table($unused), ['name' => $unused->name, 'driver' => 'manual'])
            ->assertHasNoFormErrors();

        expect($shipped->fresh()->driver)->toBe('ups')
            ->and($unused->fresh()->driver)->toBe('manual');
    });

    it('edits a carrier with shipments although its driver was never set', function (): void {
        $carrier = Carrier::factory()->create(['driver' => null]);
        OrderShipping::factory()->create(['carrier_id' => $carrier->id]);

        Livewire::test(Carriers::class)
            ->callAction(TestAction::make('edit')->table($carrier), ['name' => 'Local courier'])
            ->assertHasNoFormErrors();

        expect($carrier->fresh())
            ->name->toBe('Local courier')
            ->driver->toBeNull();
    });

    it('deletes a carrier nothing uses', function (): void {
        $carrier = Carrier::factory()->create();

        Livewire::test(Carriers::class)
            ->assertActionEnabled(TestAction::make('delete')->table($carrier))
            ->callAction(TestAction::make('delete')->table($carrier));

        expect(Carrier::query()->find($carrier->id))->toBeNull();
    });
})->group('livewire', 'settings', 'carriers');
