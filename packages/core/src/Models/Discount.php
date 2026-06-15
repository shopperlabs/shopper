<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Database\Factories\DiscountFactory;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountCondition;
use Shopper\Core\Enum\DiscountStatus;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Enum\ExclusivityClass;
use Shopper\Core\Exceptions\DiscountTermsFrozenException;
use Shopper\Core\Exceptions\DiscountZoneFrozenException;
use Shopper\Core\Models\Contracts\Discount as DiscountContract;
use Shopper\Core\Models\Traits\HasPublicId;

/**
 * @property-read int $id
 * @property-read ?string $public_id
 * @property-read string $code
 * @property-read DiscountType $type
 * @property-read int $value
 * @property-read string $apply_to
 * @property-read string $min_required
 * @property-read ?string $min_required_value
 * @property-read string $eligibility
 * @property-read int $total_use
 * @property-read ?int $usage_limit
 * @property-read bool $usage_limit_per_user
 * @property-read bool $is_active
 * @property-read ?int $campaign_id
 * @property-read ExclusivityClass $exclusivity_class
 * @property-read bool $combinable
 * @property-read int $priority
 * @property-read ?int $zone_id
 * @property-read array<string, mixed>|null $metadata
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read CarbonInterface $start_at
 * @property-read ?CarbonInterface $end_at
 * @property-read ?Zone $zone
 * @property-read ?Campaign $campaign
 * @property-read DiscountStatus $status
 * @property-read Collection<array-key, DiscountDetail> $items
 * @property-read Collection<array-key, Order> $orders
 */
class Discount extends Model implements DiscountContract
{
    /** @use HasFactory<DiscountFactory> */
    use HasFactory;

    use HasPublicId;

    protected $guarded = [];

    public static function booted(): void
    {
        self::saving(function (self $discount): void {
            if (! $discount->isDirty('zone_id')) {
                return;
            }

            if ($discount->type !== DiscountType::FixedAmount) {
                return;
            }

            if (! $discount->exists || $discount->total_use <= 0) {
                return;
            }

            throw new DiscountZoneFrozenException;
        });

        self::saving(function (self $discount): void {
            if (! $discount->exists || $discount->total_use <= 0) {
                return;
            }

            if ($discount->isDirty(['code', 'type', 'value'])) {
                throw new DiscountTermsFrozenException;
            }
        });
    }

    public function getTable(): string
    {
        return shopper_table('discounts');
    }

    public function hasReachedLimit(): bool
    {
        if ($this->usage_limit !== null) {
            return $this->total_use >= $this->usage_limit;
        }

        return false;
    }

    public function isApplicable(): bool
    {
        if ($this->apply_to !== DiscountApplyTo::Products->value) {
            return true;
        }

        $items = $this->relationLoaded('items')
            ? $this->items
            : $this->items()->with('discountable')->get();

        $productItems = $items->where('condition', DiscountCondition::ApplyTo);

        if ($productItems->isEmpty()) {
            return true;
        }

        return $productItems->contains(
            fn (DiscountDetail $item): bool => $item->discountable !== null // @phpstan-ignore notIdentical.alwaysTrue
        );
    }

    /**
     * @return HasMany<DiscountDetail, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(DiscountDetail::class, 'discount_id');
    }

    /**
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(config('shopper.models.order'), 'discount_id');
    }

    /**
     * @return BelongsTo<Zone, $this>
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    /**
     * @return BelongsTo<Campaign, $this>
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    protected static function newFactory(): DiscountFactory
    {
        return DiscountFactory::new();
    }

    /**
     * @param  Builder<Discount>  $query
     * @return Builder<Discount>
     */
    #[Scope]
    protected function active(Builder $query): Builder
    {
        $now = now();

        return $query
            ->where('is_active', true)
            ->where('start_at', '<=', $now)
            ->where(fn (Builder $inner) => $inner
                ->whereNull('end_at')
                ->orWhere('end_at', '>', $now))
            ->where(fn (Builder $inner) => $inner
                ->whereNull('usage_limit')
                ->orWhereColumn('total_use', '<', 'usage_limit'));
    }

    /**
     * @return Attribute<DiscountStatus, never>
     */
    protected function status(): Attribute
    {
        return Attribute::get(function (): DiscountStatus {
            if (! $this->exists) {
                return DiscountStatus::Draft;
            }

            if (! $this->is_active) {
                return DiscountStatus::Disabled;
            }

            if ($this->end_at !== null && $this->end_at->isPast()) {
                return DiscountStatus::Expired;
            }

            if ($this->hasReachedLimit()) {
                return DiscountStatus::LimitReached;
            }

            if ($this->start_at->isFuture()) {
                return DiscountStatus::Scheduled;
            }

            if (! $this->isApplicable()) {
                return DiscountStatus::Inapplicable;
            }

            return DiscountStatus::Active;
        });
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'usage_limit_per_user' => 'boolean',
            'combinable' => 'boolean',
            'priority' => 'integer',
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'metadata' => 'array',
            'type' => DiscountType::class,
            'exclusivity_class' => ExclusivityClass::class,
        ];
    }
}
