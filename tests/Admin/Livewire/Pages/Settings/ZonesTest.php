<?php

declare(strict_types=1);

use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\Zone;
use Shopper\Livewire\Components\Settings\Zones\Detail;
use Shopper\Livewire\Components\Settings\Zones\ZoneShippingOptions;
use Shopper\Livewire\Pages\Settings\Zones;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('system.settings');
    $this->actingAs($this->user);
});

describe(Zones::class, function (): void {
    it('can render zones settings component', function (): void {
        Livewire::test(Zones::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.settings.zones');
    });

    it('can set current zone id', function (): void {
        $zone = Zone::factory()->create();

        Livewire::test(Zones::class)
            ->set('currentZoneId', $zone->id)
            ->assertSet('currentZoneId', $zone->id)
            ->assertDispatched('zone.changed');
    });

    it('passes zones to view', function (): void {
        Zone::factory()->count(2)->create();

        $component = Livewire::test(Zones::class);

        expect($component->instance()->zones->count())->toBe(2);
    });
})->group('livewire', 'settings', 'zones');

describe(Detail::class, function (): void {
    it('refuses to delete a zone that has shipping options', function (): void {
        $zone = Zone::factory()->create();
        CarrierOption::factory()->create(['zone_id' => $zone->id]);

        Livewire::test(Detail::class, ['currentZoneId' => $zone->id])
            ->assertActionDisabled('delete')
            ->callAction('delete');

        expect(Zone::query()->find($zone->id))->not->toBeNull();
    });

    it('refuses to delete a zone orders were placed in', function (bool $deleted): void {
        $zone = Zone::factory()->create();
        $order = Order::factory()->create(['zone_id' => $zone->id]);

        if ($deleted) {
            $order->delete();
        }

        Livewire::test(Detail::class, ['currentZoneId' => $zone->id])
            ->assertActionDisabled('delete')
            ->callAction('delete');

        expect(Zone::query()->find($zone->id))->not->toBeNull()
            ->and($order->fresh()->zone_id)->toBe($zone->id);
    })->with(['placed' => false, 'soft deleted' => true]);

    it('refuses to delete a zone a discount is restricted to', function (): void {
        $zone = Zone::factory()->create();
        $discount = Discount::factory()->create(['zone_id' => $zone->id]);

        Livewire::test(Detail::class, ['currentZoneId' => $zone->id])
            ->assertActionDisabled('delete')
            ->callAction('delete');

        expect(Zone::query()->find($zone->id))->not->toBeNull()
            ->and($discount->fresh()->zone_id)->toBe($zone->id);
    });

    it('deletes a zone nothing uses', function (): void {
        $zone = Zone::factory()->create();

        Livewire::test(Detail::class, ['currentZoneId' => $zone->id])
            ->assertActionEnabled('delete')
            ->callAction('delete');

        expect(Zone::query()->find($zone->id))->toBeNull();
    });
})->group('livewire', 'settings', 'zones');

describe(ZoneShippingOptions::class, function (): void {
    it('refuses to delete a shipping option an order uses', function (bool $deleted): void {
        $option = CarrierOption::factory()->create();
        $unused = CarrierOption::factory()->create(['zone_id' => $option->zone_id]);
        $order = Order::factory()->create(['zone_id' => $option->zone_id, 'shipping_option_id' => $option->id]);

        if ($deleted) {
            $order->delete();
        }

        Livewire::test(ZoneShippingOptions::class, ['selectedZoneId' => $option->zone_id])
            ->assertActionDisabled(TestAction::make('delete')->arguments(['id' => $option->id]))
            ->assertActionEnabled(TestAction::make('delete')->arguments(['id' => $unused->id]))
            ->callAction(TestAction::make('delete')->arguments(['id' => $option->id]))
            ->callAction(TestAction::make('delete')->arguments(['id' => $option->id.'abc']));

        expect(CarrierOption::query()->find($option->id))->not->toBeNull()
            ->and($order->fresh()->shipping_option_id)->toBe($option->id);
    })->with(['placed' => false, 'soft deleted' => true]);

    it('deletes a shipping option no order uses', function (): void {
        $option = CarrierOption::factory()->create();

        Livewire::test(ZoneShippingOptions::class, ['selectedZoneId' => $option->zone_id])
            ->callAction(TestAction::make('delete')->arguments(['id' => $option->id]))
            ->assertNotified(__('shopper::notifications.delete', ['item' => __('shopper::pages/settings/zones.shipping_options.single')]));

        expect(CarrierOption::query()->find($option->id))->toBeNull();
    });
})->group('livewire', 'settings', 'zones');
