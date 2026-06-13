<?php

declare(strict_types=1);

namespace Shopper\Core\Actions;

use Illuminate\Database\Eloquent\Builder;
use Shopper\Core\Exceptions\CampaignBudgetExceededException;
use Shopper\Core\Models\Campaign;
use Shopper\Core\Models\CampaignBudgetMovement;
use Shopper\Core\Models\Contracts\Campaign as CampaignContract;

final class ReserveCampaignBudget
{
    /**
     * Atomically draw down a campaign budget for a single redemption.
     *
     * The spend and usage counters are bumped in one conditional statement so
     * concurrent checkouts can never push a campaign past its cap: the row is
     * only updated while it still fits, and an untouched row (affected === 0)
     * means the budget is exhausted. A campaign without caps still records the
     * movement so per-campaign analytics stay accurate.
     */
    public function execute(Campaign $campaign, int $spend, ?int $orderId = null, ?string $actor = null): void
    {
        $affected = resolve(CampaignContract::class)::query()
            ->whereKey($campaign->getKey())
            ->where(function (Builder $query) use ($spend): void {
                $query->whereNull('budget_amount')
                    ->orWhereRaw('spent_amount + ? <= budget_amount', [$spend]);
            })
            ->where(function (Builder $query): void {
                $query->whereNull('budget_count')
                    ->orWhereColumn('used_count', '<', 'budget_count');
            })
            ->incrementEach([
                'spent_amount' => $spend,
                'used_count' => 1,
            ]);

        if ($affected === 0) {
            throw CampaignBudgetExceededException::make($campaign->name);
        }

        $balanceAfter = (int) resolve(CampaignContract::class)::query()
            ->whereKey($campaign->getKey())
            ->value('spent_amount');

        CampaignBudgetMovement::query()->create([
            'campaign_id' => $campaign->getKey(),
            'order_id' => $orderId,
            'amount' => $spend,
            'balance_after' => $balanceAfter,
            'actor' => $actor,
        ]);
    }
}
