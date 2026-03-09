<?php

declare(strict_types=1);

namespace Shopper\Actions\Store;

use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Models\Discount;
use Shopper\Jobs\AttachedDiscountToCustomers;
use Shopper\Jobs\AttachedDiscountToProducts;

final readonly class SaveAndDispatchDiscountAction
{
    /**
     * @param  array<string, mixed>  $values
     * @param  array<int>  $productsIds
     * @param  array<int>  $customersIds
     */
    public function __invoke(
        array $values,
        ?int $discountId = null,
        array $productsIds = [],
        array $customersIds = []
    ): Discount {
        if ($discountId) {
            $discount = Discount::query()->findOrFail($discountId);
            $discount->update($values);
        } else {
            $discount = Discount::query()->create($values);
        }

        $applyTo = data_get($values, 'apply_to');
        $eligibility = data_get($values, 'eligibility');

        AttachedDiscountToProducts::dispatch(
            $applyTo instanceof DiscountApplyTo ? $applyTo : DiscountApplyTo::from($applyTo),
            $productsIds,
            $discount,
        );

        AttachedDiscountToCustomers::dispatch(
            $eligibility instanceof DiscountEligibility ? $eligibility : DiscountEligibility::from($eligibility),
            $customersIds,
            $discount,
        );

        return $discount;
    }
}
