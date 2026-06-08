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
        if ($this->eligibility !== DiscountEligibility::Customers) {
            $this->discount->items()
                ->where('condition', DiscountCondition::Eligibility)
                ->delete();

            return;
        }

        // Drop the customers that are no longer selected.
        $this->discount->items()
            ->where('condition', DiscountCondition::Eligibility)
            ->whereNotIn('discountable_id', $this->customersIds)
            ->delete();

        if ($this->customersIds === []) {
            return;
        }

        // Attach the selected customers in a single statement, preserving the
        // usage counter of rows that already exist.
        $type = config('auth.providers.users.model');
        $now = now();

        DiscountDetail::query()->upsert(
            array_map(fn (int $customerId): array => [
                'discount_id' => $this->discount->id,
                'discountable_id' => $customerId,
                'discountable_type' => $type,
                'condition' => DiscountCondition::Eligibility->value,
                'total_use' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ], $this->customersIds),
            uniqueBy: ['discount_id', 'discountable_type', 'discountable_id'],
            update: ['condition', 'updated_at'],
        );
    }
}
