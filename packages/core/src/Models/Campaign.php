<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Database\Factories\CampaignFactory;
use Shopper\Core\Enum\CampaignBudgetType;
use Shopper\Core\Enum\CampaignStatus;
use Shopper\Core\Models\Contracts\Campaign as CampaignContract;
use Shopper\Core\Models\Traits\HasPublicId;

/**
 * @property-read int $id
 * @property-read ?string $public_id
 * @property-read bool $is_active
 * @property-read string $name
 * @property-read string $currency_code
 * @property-read CampaignBudgetType $budget_type
 * @property-read ?int $budget_amount
 * @property-read ?int $budget_count
 * @property-read int $spent_amount
 * @property-read int $used_count
 * @property-read CarbonInterface $starts_at
 * @property-read ?CarbonInterface $ends_at
 * @property-read array<string, mixed>|null $metadata
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read CampaignStatus $status
 * @property-read Collection<array-key, Discount> $discounts
 */
class Campaign extends Model implements CampaignContract
{
    /** @use HasFactory<CampaignFactory> */
    use HasFactory;

    use HasPublicId;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('campaigns');
    }

    public function hasReachedBudget(): bool
    {
        $spendReached = $this->budget_type->hasSpendCap()
            && $this->budget_amount !== null
            && $this->spent_amount >= $this->budget_amount;

        $countReached = $this->budget_type->hasCountCap()
            && $this->budget_count !== null
            && $this->used_count >= $this->budget_count;

        return $spendReached || $countReached;
    }

    /**
     * @return HasMany<Discount, $this>
     */
    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class, 'campaign_id');
    }

    protected static function newFactory(): CampaignFactory
    {
        return CampaignFactory::new();
    }

    /**
     * @return Attribute<CampaignStatus, never>
     */
    protected function status(): Attribute
    {
        return Attribute::get(function (): CampaignStatus {
            if (! $this->exists) {
                return CampaignStatus::Draft;
            }

            if (! $this->is_active) {
                return CampaignStatus::Disabled;
            }

            if ($this->ends_at !== null && $this->ends_at->isPast()) {
                return CampaignStatus::Expired;
            }

            if ($this->hasReachedBudget()) {
                return CampaignStatus::BudgetExhausted;
            }

            if ($this->starts_at->isFuture()) {
                return CampaignStatus::Scheduled;
            }

            return CampaignStatus::Active;
        });
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'budget_type' => CampaignBudgetType::class,
            'budget_amount' => 'integer',
            'budget_count' => 'integer',
            'spent_amount' => 'integer',
            'used_count' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'metadata' => 'array',
        ];
    }
}
