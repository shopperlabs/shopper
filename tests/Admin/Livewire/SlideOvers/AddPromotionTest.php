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
use Shopper\Core\Models\Campaign;
use Shopper\Core\Models\Discount;
use Shopper\Livewire\SlideOvers\AddPromotion;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    setupCurrencies();

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('discounts.create', 'discounts.edit');
    $this->actingAs($this->user);
});

describe(AddPromotion::class, function (): void {
    it('refuses access without the discounts.create permission', function (): void {
        $this->actingAs(User::factory()->create());

        Livewire::test(AddPromotion::class)
            ->assertForbidden();
    });

    it('creates a promotion with the engine fields and redirects to edit', function (): void {
        $campaign = Campaign::factory()->create(['currency_code' => 'USD']);
        $products = Product::factory()->count(2)->publish()->create();

        Livewire::test(AddPromotion::class)
            ->fillForm([
                'type' => DiscountType::FixedAmount->value,
                'apply_to' => DiscountApplyTo::Products->value,
                'trigger' => PromotionSource::Code->value,
                'code' => 'SUMMER23',
                'value' => 1000,
                'products' => $products->pluck('id')->toArray(),
                'eligibility' => DiscountEligibility::Everyone->value,
                'min_required' => DiscountRequirement::None->value,
                'exclusivity_class' => ExclusivityClass::Product->value,
                'combinable' => true,
                'priority' => 5,
                'campaign_id' => $campaign->id,
                'is_active' => true,
                'start_at' => now(),
            ])
            ->call('store')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $discount = Discount::query()->firstOrFail();

        expect(Discount::query()->count())->toBe(1)
            ->and($discount->trigger)->toBe(PromotionSource::Code)
            ->and($discount->exclusivity_class)->toBe(ExclusivityClass::Product)
            ->and($discount->combinable)->toBeTrue()
            ->and($discount->priority)->toBe(5)
            ->and($discount->campaign_id)->toBe($campaign->id)
            ->and($discount->items()->where('condition', DiscountCondition::ApplyTo)->count())->toBe(2);
    });

    it('creates an automatic promotion without a code', function (): void {
        Livewire::test(AddPromotion::class)
            ->fillForm([
                'type' => DiscountType::Percentage->value,
                'apply_to' => DiscountApplyTo::Order->value,
                'trigger' => PromotionSource::Automatic->value,
                'value' => 10,
                'eligibility' => DiscountEligibility::Everyone->value,
                'min_required' => DiscountRequirement::None->value,
                'is_active' => true,
                'start_at' => now(),
            ])
            ->call('store')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $discount = Discount::query()->firstOrFail();

        expect($discount->trigger)->toBe(PromotionSource::Automatic)
            ->and($discount->code)->toBeNull();
    });

    it('rejects a percentage value above 100', function (): void {
        Livewire::test(AddPromotion::class)
            ->fillForm([
                'type' => DiscountType::Percentage->value,
                'apply_to' => DiscountApplyTo::Order->value,
                'trigger' => PromotionSource::Code->value,
                'code' => 'OVER',
                'value' => 150,
                'eligibility' => DiscountEligibility::Everyone->value,
                'min_required' => DiscountRequirement::None->value,
                'start_at' => now(),
            ])
            ->call('store')
            ->assertHasFormErrors(['value']);

        expect(Discount::query()->count())->toBe(0);
    });

    it('rejects a start date in the past', function (): void {
        Livewire::test(AddPromotion::class)
            ->fillForm([
                'type' => DiscountType::Percentage->value,
                'apply_to' => DiscountApplyTo::Order->value,
                'trigger' => PromotionSource::Code->value,
                'code' => 'PAST',
                'value' => 10,
                'eligibility' => DiscountEligibility::Everyone->value,
                'min_required' => DiscountRequirement::None->value,
                'start_at' => now()->subWeek(),
            ])
            ->call('store')
            ->assertHasFormErrors(['start_at']);
    });

    it('does not create a discount when `apply_to` is `Products` and none are selected', function (): void {
        Livewire::test(AddPromotion::class)
            ->fillForm([
                'type' => DiscountType::Percentage->value,
                'apply_to' => DiscountApplyTo::Products->value,
                'trigger' => PromotionSource::Code->value,
                'code' => 'NOPRODUCTS',
                'value' => 10,
                'eligibility' => DiscountEligibility::Everyone->value,
                'min_required' => DiscountRequirement::None->value,
                'is_active' => true,
                'start_at' => now(),
            ])
            ->call('store')
            ->assertNoRedirect();

        expect(Discount::query()->count())->toBe(0);
    });

    it('does not create a discount when `eligibility` is `Customers` and none are selected', function (): void {
        Livewire::test(AddPromotion::class)
            ->fillForm([
                'type' => DiscountType::Percentage->value,
                'apply_to' => DiscountApplyTo::Order->value,
                'trigger' => PromotionSource::Code->value,
                'code' => 'NOCUSTOMERS',
                'value' => 10,
                'eligibility' => DiscountEligibility::Customers->value,
                'min_required' => DiscountRequirement::None->value,
                'is_active' => true,
                'start_at' => now(),
            ])
            ->call('store')
            ->assertNoRedirect();

        expect(Discount::query()->count())->toBe(0);
    });

    it('populates and removes products through the picker events', function (): void {
        $products = Product::factory()->count(2)->publish()->create();
        [$keep, $drop] = $products->pluck('id')->map(fn ($id): int => (int) $id)->all();

        $component = Livewire::test(AddPromotion::class)
            ->dispatch('shopper.discount.products.selected', ids: [$keep])
            ->dispatch('shopper.discount.products.selected', ids: [$keep, $drop])
            ->assertSet('data.products', [$keep, $drop]);

        expect($component->instance()->selectedProducts)->toHaveCount(2);

        $component->call('removeProductFromDiscount', $drop)
            ->assertSet('data.products', [$keep]);
    });
})->group('livewire', 'discounts');
