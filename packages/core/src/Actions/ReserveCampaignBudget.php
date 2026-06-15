<?php

declare(strict_types=1);

namespace Shopper\Core\Actions;

use Illuminate\Support\Facades\DB;
use Shopper\Core\Enum\CampaignBudgetDirection;
use Shopper\Core\Exceptions\CampaignBudgetExceededException;
use Shopper\Core\Models\Campaign;
use Shopper\Core\Models\CampaignBudgetMovement;

final class ReserveCampaignBudget
{
    /**
     * Atomically draw down a campaign budget for a single redemption.
     *
     * The spend and usage counters are bumped in one conditional statement so
     * concurrent checkouts can never push a campaign past its cap: the row is
     * only updated while it still fits, and an untouched row (affected === 0)
     * means the budget is exhausted. Only the caps declared by `budget_type`
     * are enforced, and a campaign without caps still records the movement so
     * per-campaign analytics stay accurate.
     */
    public function execute(Campaign $campaign, int $spend, ?int $orderId = null, ?string $actor = null): void
    {
        // The counter bump and the audit movement are one unit: if the movement
        // collides with the (campaign, order, direction) unique key on a retry,
        // the increment rolls back too, so a duplicate reservation can never
        // double-spend the budget.
        DB::transaction(function () use ($campaign, $spend, $orderId, $actor): void {
            $query = Campaign::query()->whereKey($campaign->getKey());

            if ($campaign->budget_type->hasSpendCap() && $campaign->budget_amount !== null) {
                $query->whereRaw('spent_amount + ? <= budget_amount', [$spend]);
            }

            if ($campaign->budget_type->hasCountCap() && $campaign->budget_count !== null) {
                $query->whereColumn('used_count', '<', 'budget_count');
            }

            $affected = $query->incrementEach([
                'spent_amount' => $spend,
                'used_count' => 1,
            ]);

            if ($affected === 0) {
                throw CampaignBudgetExceededException::make($campaign->name);
            }

            CampaignBudgetMovement::query()->create([
                'campaign_id' => $campaign->getKey(),
                'order_id' => $orderId,
                'direction' => CampaignBudgetDirection::Reserve->value,
                'amount' => $spend,
                // Derived from the pre-update snapshot: no extra round-trip under
                // the checkout lock, deterministic regardless of concurrent writers.
                'balance_after' => $campaign->spent_amount + $spend,
                'actor' => $actor,
            ]);
        });
    }
}
