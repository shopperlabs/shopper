<?php

declare(strict_types=1);

namespace Shopper\Cart\Discounts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Shopper\Core\Enum\DiscountCondition;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\DiscountDetail;

final readonly class SyncDiscountEligibilityAction
{
    public function __construct(
        private DiscountEligibilityManager $manager,
    ) {}

    /**
     * @param  array<int>  $ids
     */
    public function execute(Discount $discount, string $eligibility, array $ids): void
    {
        $type = $this->manager->for($eligibility)?->discountableType();

        DB::transaction(function () use ($discount, $type, $ids): void {
            if ($type === null) {
                $discount->items()
                    ->where('condition', DiscountCondition::Eligibility)
                    ->delete();

                return;
            }

            $discount->items()
                ->where('condition', DiscountCondition::Eligibility)
                ->where(function (Builder $query) use ($type, $ids): void {
                    $query->where('discountable_type', '!=', $type)
                        ->orWhereNotIn('discountable_id', $ids);
                })
                ->delete();

            if ($ids === []) {
                return;
            }

            $now = now();

            DiscountDetail::query()->upsert(
                array_map(fn (int $id): array => [
                    'discount_id' => $discount->id,
                    'discountable_id' => $id,
                    'discountable_type' => $type,
                    'condition' => DiscountCondition::Eligibility->value,
                    'total_use' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], $ids),
                uniqueBy: ['discount_id', 'discountable_type', 'discountable_id'],
                update: ['condition', 'updated_at'],
            );
        });
    }
}
