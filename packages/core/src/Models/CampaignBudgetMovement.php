<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Core\Enum\CampaignBudgetDirection;

/**
 * @property-read int $id
 * @property-read int $campaign_id
 * @property-read ?int $order_id
 * @property-read CampaignBudgetDirection $direction
 * @property-read int $amount
 * @property-read int $balance_after
 * @property-read ?string $actor
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read Campaign $campaign
 */
class CampaignBudgetMovement extends Model
{
    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('campaign_budget_movements');
    }

    /**
     * @return BelongsTo<Campaign, $this>
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    protected function casts(): array
    {
        return [
            'direction' => CampaignBudgetDirection::class,
            'amount' => 'integer',
            'balance_after' => 'integer',
        ];
    }
}
