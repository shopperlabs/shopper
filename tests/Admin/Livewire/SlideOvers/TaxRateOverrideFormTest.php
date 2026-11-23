<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\TaxRate;
use Shopper\Core\Models\TaxRateRule;
use Shopper\Core\Models\TaxZone;
use Shopper\Livewire\SlideOvers\TaxRateOverrideForm;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('system.settings');
    $this->actingAs($this->user);

    $this->taxZone = TaxZone::factory()->create();
});

describe(TaxRateOverrideForm::class, function (): void {
    it('saves an override that targets the shipping', function (): void {
        Livewire::test(TaxRateOverrideForm::class, ['taxZoneId' => $this->taxZone->id])
            ->fillForm([
                'name' => 'Shipping exempt',
                'rate' => 0,
                'targets' => [
                    ['reference_type' => 'shipping', 'reference_id' => 'shipping'],
                ],
            ])
            ->call('store')
            ->assertHasNoFormErrors();

        $rate = TaxRate::query()->where('name', 'Shipping exempt')->sole();

        expect(TaxRateRule::query()->where('tax_rate_id', $rate->id)->sole())
            ->reference_type->toBe('shipping')
            ->reference_id->toBe('shipping');
    });
});
