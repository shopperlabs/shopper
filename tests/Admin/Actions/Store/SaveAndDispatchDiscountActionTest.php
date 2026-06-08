<?php

declare(strict_types=1);

use Shopper\Actions\Store\SaveAndDispatchDiscountAction;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountCondition;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
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
            'customersIds' => $this->users->pluck('id')->toArray(),
        ]);

        $discount->refresh();

        expect($discount->code)->toBe($code)
            ->and($discount->items()->where('condition', DiscountCondition::ApplyTo)->count())->toBe(1)
            ->and($discount->items()->where('condition', DiscountCondition::Eligibility)->count())->toBe(3);
    });
})->group('discount');
