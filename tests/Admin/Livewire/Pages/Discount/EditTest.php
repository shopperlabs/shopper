<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountCondition;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Enum\ExclusivityClass;
use Shopper\Core\Enum\PromotionSource;
use Shopper\Core\Exceptions\DiscountZoneFrozenException;
use Shopper\Core\Models\Campaign;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Zone;
use Shopper\Livewire\Pages\Discount\Edit;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('discounts.create', 'discounts.edit', 'discounts.delete');
    $this->actingAs($this->user);

    $this->customers = User::factory()->count(3)->create()->each(function ($user): void {
        $user->assignRole(config('shopper.admin.roles.user'));
    });
});

describe(Edit::class, function (): void {
    it('updates an existing discount', function (): void {
        $discount = Discount::factory()->create([
            'type' => DiscountType::Percentage,
            'value' => 15,
        ]);

        Livewire::test(Edit::class, ['record' => $discount->id])
            ->fillForm([
                'code' => $code = 'LAURE_MONNEY_2025',
                'apply_to' => DiscountApplyTo::Order,
                'min_required' => DiscountRequirement::None,
                'eligibility' => DiscountEligibility::Customers,
                'customers' => $this->customers->pluck('id')->toArray(),
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $discount->refresh();

        expect($discount->code)->toBe($code)
            ->and($discount->items()->where('condition', DiscountCondition::Eligibility)->count())->toBe(3);
    });

    it('updates the combination settings and campaign link', function (): void {
        $campaign = Campaign::factory()->create();
        $discount = Discount::factory()->create([
            'type' => DiscountType::Percentage,
            'value' => 15,
        ]);

        Livewire::test(Edit::class, ['record' => $discount->id])
            ->fillForm([
                'apply_to' => DiscountApplyTo::Order,
                'min_required' => DiscountRequirement::None,
                'eligibility' => DiscountEligibility::Everyone,
                'exclusivity_class' => ExclusivityClass::Shipping->value,
                'combinable' => true,
                'priority' => 7,
                'campaign_id' => $campaign->id,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $discount->refresh();

        expect($discount->exclusivity_class)->toBe(ExclusivityClass::Shipping)
            ->and($discount->combinable)->toBeTrue()
            ->and($discount->priority)->toBe(7)
            ->and($discount->campaign_id)->toBe($campaign->id);
    });

    it('declares the discount property as Locked', function (): void {
        $reflection = new ReflectionClass(Edit::class);
        $property = $reflection->getProperty('discount');

        $names = array_map(
            fn (ReflectionAttribute $attr): string => $attr->getName(),
            $property->getAttributes(),
        );

        expect($names)->toContain(\Livewire\Attributes\Locked::class);
    });

    it('throws when changing the zone of a fixed-amount discount that has been used', function (): void {
        $eur = Currency::query()->firstOrCreate(['code' => 'EUR'], ['name' => 'Euro', 'symbol' => '€']);
        $usd = Currency::query()->firstOrCreate(['code' => 'USD'], ['name' => 'Dollar', 'symbol' => '$']);

        $zoneEu = Zone::query()->create(['name' => 'EU', 'code' => 'EU', 'currency_id' => $eur->id]);
        $zoneUs = Zone::query()->create(['name' => 'US', 'code' => 'US', 'currency_id' => $usd->id]);

        $discount = Discount::factory()->create([
            'type' => DiscountType::FixedAmount,
            'zone_id' => $zoneEu->id,
            'total_use' => 5,
        ]);

        expect(fn () => $discount->update(['zone_id' => $zoneUs->id]))
            ->toThrow(DiscountZoneFrozenException::class);
    });

    it('throws ModelNotFoundException for a non existing discount', function (): void {
        Livewire::test(Edit::class, ['record' => 999_999]);
    })->throws(Illuminate\Database\Eloquent\ModelNotFoundException::class);

    it('refuses access without the discounts.edit permission', function (): void {
        $discount = Discount::factory()->create();

        $stranger = User::factory()->create();
        $this->actingAs($stranger);

        Livewire::test(Edit::class, ['record' => $discount->id])
            ->assertForbidden();
    });

    it('renders the edit page for an automatic discount without a code', function (): void {
        $discount = Discount::factory()->create([
            'trigger' => PromotionSource::Automatic->value,
            'code' => null,
        ]);

        Livewire::test(Edit::class, ['record' => $discount->id])
            ->assertOk();
    });
})->group('livewire', 'pages', 'discounts');
