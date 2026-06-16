<?php

declare(strict_types=1);

namespace Shopper\Actions\Store;

use Illuminate\Support\Arr;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\PromotionSource;
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
        array $customersIds = [],
        ?string $trigger = null,
        ?int $campaignId = null
    ): Discount {
        $values = Arr::except($values, ['total_use', 'campaign_id', 'public_id', 'trigger']);

        if ($discountId) {
            $discount = Discount::query()->findOrFail($discountId);
            $discount->update($values);
        } else {
            $discount = Discount::query()->create($values);
        }

        if ($trigger !== null) {
            $discount->trigger = PromotionSource::from($trigger);
        }

        if ($campaignId !== null) {
            $discount->campaign_id = $campaignId;
        } elseif ($discount->campaign_id !== null && $discount->total_use === 0) {
            $discount->campaign_id = null;
        }

        if ($discount->isDirty(['trigger', 'campaign_id'])) {
            $discount->save();
        }

        $applyTo = data_get($values, 'apply_to');
        $eligibility = data_get($values, 'eligibility');

        AttachedDiscountToProducts::dispatchSync(
            $applyTo instanceof DiscountApplyTo ? $applyTo : DiscountApplyTo::from($applyTo),
            $productsIds,
            $discount,
        );

        AttachedDiscountToCustomers::dispatchSync(
            $eligibility instanceof DiscountEligibility ? $eligibility : DiscountEligibility::from($eligibility),
            $customersIds,
            $discount,
        );

        return $discount;
    }
}
