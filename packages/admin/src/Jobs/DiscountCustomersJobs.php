<?php

declare(strict_types=1);

namespace Shopper\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\DiscountDetail;
use Shopper\Core\Models\User;

class DiscountCustomersJobs implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public string $eligibility,
        public array $customersIds,
        public Discount $discount
    ) {}

    public function handle(): void
    {
        if ($this->eligibility === DiscountEligibility::Customers()) {
            // Remove all the customers that's not been selected that already exist during creation of the discount
            $this->discount->items()
                ->where('condition', 'eligibility')
                ->whereNotIn('discountable_id', $this->customersIds)
                ->delete();

            // Create or Update the associate the discount to all the selected users.
            foreach ($this->customersIds as $customerId) {
                DiscountDetail::query()->updateOrCreate(
                    attributes: [
                        'discount_id' => $this->discount->id,
                        'discountable_id' => $customerId,
                        'discountable_type' => config('auth.providers.users.model', User::class),
                    ],
                    values: ['condition' => 'eligibility']
                );
            }
        } else {
            $this->discount->items()
                ->where('condition', 'eligibility')
                ->delete();
        }
    }
}
