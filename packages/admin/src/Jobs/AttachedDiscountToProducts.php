<?php

declare(strict_types=1);

namespace Shopper\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountCondition;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\DiscountDetail;

class AttachedDiscountToProducts implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public DiscountApplyTo $applyTo,
        public array $productIds,
        public Discount $discount
    ) {}

    public function handle(): void
    {
        if ($this->applyTo !== DiscountApplyTo::Products) {
            $this->discount->items()
                ->where('condition', DiscountCondition::ApplyTo)
                ->delete();

            return;
        }

        // Drop the products that are no longer selected.
        $this->discount->items()
            ->where('condition', DiscountCondition::ApplyTo)
            ->whereNotIn('discountable_id', $this->productIds)
            ->delete();

        if ($this->productIds === []) {
            return;
        }

        // Attach the selected products in a single statement, preserving the
        // usage counter of rows that already exist.
        $type = config('shopper.models.product');
        $now = now();

        DiscountDetail::query()->upsert(
            array_map(fn (int $productId): array => [
                'discount_id' => $this->discount->id,
                'discountable_id' => $productId,
                'discountable_type' => $type,
                'condition' => DiscountCondition::ApplyTo->value,
                'total_use' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ], $this->productIds),
            uniqueBy: ['discount_id', 'discountable_type', 'discountable_id'],
            update: ['condition', 'updated_at'],
        );
    }
}
