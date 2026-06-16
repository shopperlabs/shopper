<?php

declare(strict_types=1);

namespace Shopper\Core\Actions;

use Illuminate\Support\Facades\DB;
use Shopper\Core\Enum\CampaignBudgetDirection;
use Shopper\Core\Models\Campaign;
use Shopper\Core\Models\CampaignBudgetMovement;

final class ReleaseCampaignBudget
{
    /**
     * Give a campaign budget back when its order is cancelled or refunded, so a
     * voided redemption no longer counts against the cap. The exact reserved
     * amount is returned (read from the original reservation movement), the
     * counters are floored at zero, and a Release movement makes the operation
     * idempotent: a retried refund webhook releases the budget at most once.
     */
    public function execute(Campaign $campaign, int $orderId, ?string $actor = null): void
    {
        DB::transaction(function () use ($campaign, $orderId, $actor): void {
            $locked = Campaign::query()
                ->whereKey($campaign->getKey())
                ->lockForUpdate()
                ->first();

            if ($locked === null) {
                return;
            }

            $reservation = CampaignBudgetMovement::query()
                ->where('campaign_id', $locked->getKey())
                ->where('order_id', $orderId)
                ->where('direction', CampaignBudgetDirection::Reserve->value)
                ->first();

            if ($reservation === null) {
                return;
            }

            $alreadyReleased = CampaignBudgetMovement::query()
                ->where('campaign_id', $locked->getKey())
                ->where('order_id', $orderId)
                ->where('direction', CampaignBudgetDirection::Release->value)
                ->exists();

            if ($alreadyReleased) {
                return;
            }

            $amount = $reservation->amount;
            $balanceAfter = max(0, $locked->spent_amount - $amount);

            $locked->update([
                'spent_amount' => $balanceAfter,
                'used_count' => max(0, $locked->used_count - 1),
            ]);

            CampaignBudgetMovement::query()->create([
                'campaign_id' => $locked->getKey(),
                'order_id' => $orderId,
                'direction' => CampaignBudgetDirection::Release->value,
                'amount' => -$amount,
                'balance_after' => $balanceAfter,
                'actor' => $actor,
            ]);
        });
    }
}
