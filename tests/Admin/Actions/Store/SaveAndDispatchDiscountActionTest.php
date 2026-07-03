<?php

declare(strict_types=1);

use Shopper\Actions\Store\SaveAndDispatchDiscountAction;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountCondition;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Enum\PromotionSource;
use Shopper\Core\Models\Campaign;
use Shopper\Core\Models\Discount;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

/**
 * @var Tests\Admin\TestCase $this
 */
beforeEach(function (): void {
    $this->products = Product::factory()->count(3)->publish()->create();
    $this->users = User::factory()->count(3)->create();
    $this->formValues = [
        'code' => fake()->unique()->regexify('[A-Z0-9]{8}'),
        'is_active' => true,
        'type' => DiscountType::FixedAmount,
        'value' => 1000,
        'apply_to' => DiscountApplyTo::Products,
        'min_required' => DiscountRequirement::None,
        'eligibility' => DiscountEligibility::Everyone,
        'start_at' => now(),
    ];
});

describe(SaveAndDispatchDiscountAction::class, function (): void {
    it('should store a new discount', function (): void {
        $discount = app()->call(SaveAndDispatchDiscountAction::class, [
            'values' => $this->formValues,
        ]);

        expect($discount)->toBeInstanceOf(Discount::class)
            ->and($discount->code)->toBe($this->formValues['code'])
            ->and($discount->items()->count())->toBe(0);
    });

    it('writes the product pivot rows synchronously', function (): void {
        $discount = app()->call(SaveAndDispatchDiscountAction::class, [
            'values' => $this->formValues,
            'productsIds' => [$this->products->first()->id],
        ]);

        expect($discount->items()->where('condition', DiscountCondition::ApplyTo)->count())->toBe(1);
    });

    it('writes the product and customer pivot rows synchronously on update', function (): void {
        $discount = Discount::factory()->create();

        app()->call(SaveAndDispatchDiscountAction::class, [
            'values' => array_merge($this->formValues, [
                'code' => $code = 'LAURE_MONNEY_2025',
                'apply_to' => DiscountApplyTo::Products,
                'value' => 5000,
                'min_required' => DiscountRequirement::Price,
                'eligibility' => DiscountEligibility::Customers,
            ]),
            'productsIds' => [$this->products->first()->id],
            'discountId' => $discount->id,
            'eligibilityIds' => $this->users->pluck('id')->toArray(),
        ]);

        $discount->refresh();

        expect($discount->code)->toBe($code)
            ->and($discount->items()->where('condition', DiscountCondition::ApplyTo)->count())->toBe(1)
            ->and($discount->items()->where('condition', DiscountCondition::Eligibility)->count())->toBe(3);
    });

    it('never lets the form write the redemption counter or the campaign link', function (): void {
        $discount = Discount::factory()->create([
            'code' => 'KEEPME',
            'type' => DiscountType::FixedAmount,
            'value' => 1000,
            'apply_to' => DiscountApplyTo::Order,
            'total_use' => 4,
        ]);

        app()->call(SaveAndDispatchDiscountAction::class, [
            'values' => [
                'code' => 'KEEPME',
                'is_active' => true,
                'type' => DiscountType::FixedAmount,
                'value' => 1000,
                'apply_to' => DiscountApplyTo::Order,
                'min_required' => DiscountRequirement::None,
                'eligibility' => DiscountEligibility::Everyone,
                'start_at' => now(),
                'total_use' => 0,
                'campaign_id' => 999,
            ],
            'discountId' => $discount->id,
        ]);

        $discount->refresh();

        expect($discount->total_use)->toBe(4)
            ->and($discount->campaign_id)->toBeNull();
    });

    it('persists the `trigger` from the explicit parameter, ignoring `values`', function (): void {
        $discount = app()->call(SaveAndDispatchDiscountAction::class, [
            'values' => array_merge($this->formValues, [
                'trigger' => PromotionSource::Automatic->value,
            ]),
            'trigger' => PromotionSource::Code->value,
        ]);

        expect($discount->trigger)->toBe(PromotionSource::Code);
    });

    it('persists the `campaign_id` from the explicit parameter, ignoring `values`', function (): void {
        $campaign = Campaign::factory()->create();

        $discount = app()->call(SaveAndDispatchDiscountAction::class, [
            'values' => array_merge($this->formValues, [
                'campaign_id' => 999,
            ]),
            'campaignId' => $campaign->id,
        ]);

        expect($discount->campaign_id)->toBe($campaign->id);
    });

    it('detaches the campaign from an unused discount when none is provided', function (): void {
        $campaign = Campaign::factory()->create();
        $discount = Discount::factory()->create([
            'campaign_id' => $campaign->id,
            'total_use' => 0,
        ]);

        app()->call(SaveAndDispatchDiscountAction::class, [
            'values' => $this->formValues,
            'discountId' => $discount->id,
            'campaignId' => null,
        ]);

        expect($discount->refresh()->campaign_id)->toBeNull();
    });

    it('keeps the campaign attached when the discount has already been used', function (): void {
        $campaign = Campaign::factory()->create();
        $discount = Discount::factory()->create(array_merge($this->formValues, [
            'campaign_id' => $campaign->id,
            'total_use' => 3,
        ]));

        app()->call(SaveAndDispatchDiscountAction::class, [
            'values' => $this->formValues,
            'discountId' => $discount->id,
            'campaignId' => null,
        ]);

        expect($discount->refresh()->campaign_id)->toBe($campaign->id);
    });
})->group('discount');
