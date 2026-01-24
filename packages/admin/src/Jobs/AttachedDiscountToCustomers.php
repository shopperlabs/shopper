<?php

declare(strict_types=1);

namespace Shopper\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Shopper\Core\Enum\DiscountCondition;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\DiscountDetail;

class AttachedDiscountToCustomers implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param  array<int>  $customersIds
     */
    public function __construct(
        public DiscountEligibility $eligibility,
        public array $customersIds,
        public Discount $discount
    ) {}

    public function handle(): void
    {
        if ($this->eligibility === DiscountEligibility::Customers) {
            // Remove all the customers that's not been selected that already exist during creation of the discount
            $this->discount->items()
                ->where('condition', DiscountCondition::Eligibility)
                ->whereNotIn('discountable_id', $this->customersIds)
                ->delete();

            // Create or Update the associate the discount to all the selected users.
            foreach ($this->customersIds as $customerId) {
                DiscountDetail::query()->updateOrCreate(
                    attributes: [
                        'discount_id' => $this->discount->id,
                        'discountable_id' => $customerId,
                        'discountable_type' => config('auth.providers.users.model'),
                    ],
                    values: ['condition' => DiscountCondition::Eligibility]
                );
            }
        } else {
            $this->discount->items()
                ->where('condition', DiscountCondition::Eligibility)
                ->delete();
        }
    }
}
