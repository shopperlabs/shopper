<?php

declare(strict_types=1);

namespace Shopper\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Shopper\Cart\Discounts\SyncDiscountEligibilityAction;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Models\Discount;

/**
 * @deprecated Use {@see SyncDiscountEligibilityAction}.
 */
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
        resolve(SyncDiscountEligibilityAction::class)->execute(
            $this->discount,
            $this->eligibility->value,
            $this->customersIds,
        );
    }
}
