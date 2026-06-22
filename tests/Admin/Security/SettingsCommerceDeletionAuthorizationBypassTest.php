<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\TaxZone;
use Shopper\Core\Models\Zone;
use Shopper\Livewire\Components\Settings\Taxes\Detail as TaxZoneDetail;
use Shopper\Livewire\Components\Settings\Zones\Detail as ZoneDetail;
use Shopper\Livewire\Components\Settings\Zones\ZoneShippingOptions;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->dashboardUser = User::factory()->create();
    $this->dashboardUser->givePermissionTo('access_dashboard');
});

it('hides the zone delete action from a user without `access_setting`', function (): void {
    $zone = Zone::factory()->create();
    $this->actingAs($this->dashboardUser, config('shopper.auth.guard'));

    Livewire::test(ZoneDetail::class, ['currentZoneId' => $zone->id])
        ->assertActionHidden('delete');

    expect(Zone::query()->whereKey($zone->id)->exists())->toBeTrue();
});

it('hides the shipping option delete action from a user without `access_setting`', function (): void {
    $option = CarrierOption::factory()->create();
    $this->actingAs($this->dashboardUser, config('shopper.auth.guard'));

    Livewire::test(ZoneShippingOptions::class, ['selectedZoneId' => $option->zone_id])
        ->assertActionHidden('delete');

    expect(CarrierOption::query()->whereKey($option->id)->exists())->toBeTrue();
});

it('hides the tax zone delete action from a user without `access_setting`', function (): void {
    $taxZone = TaxZone::factory()->create();
    $this->actingAs($this->dashboardUser, config('shopper.auth.guard'));

    Livewire::test(TaxZoneDetail::class, ['currentTaxZoneId' => $taxZone->id])
        ->assertActionHidden('delete');

    expect(TaxZone::query()->whereKey($taxZone->id)->exists())->toBeTrue();
});

it('allows a user with `access_setting` to delete a commerce settings record', function (): void {
    $user = User::factory()->create();
    $user->givePermissionTo('access_dashboard');
    $user->givePermissionTo('access_setting');
    $this->actingAs($user, config('shopper.auth.guard'));

    $option = CarrierOption::factory()->create();

    Livewire::test(ZoneShippingOptions::class, ['selectedZoneId' => $option->zone_id])
        ->assertActionVisible('delete')
        ->callAction('delete', arguments: ['id' => $option->id]);

    expect(CarrierOption::query()->whereKey($option->id)->exists())->toBeFalse();
});
