<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Shopper\Core\Database\Factories\DiscountDetailFactory;
use Shopper\Core\Models\Contracts\DiscountDetail as DiscountDetailContract;

/**
 * @property-read int $id
 * @property-read string $condition
 * @property-read string $discountable_type
 * @property-read int $discountable_id
 * @property-read int $discount_id
 * @property-read int $total_use
 * @property-read Contracts\Discount $discount
 * @property-read Model $discountable
 */
class DiscountDetail extends Model implements DiscountDetailContract
{
    /** @use HasFactory<DiscountDetailFactory> */
    use HasFactory;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('discountables');
    }

    /**
     * @return BelongsTo<Discount, $this>
     */
    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function discountable(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function newFactory(): DiscountDetailFactory
    {
        return DiscountDetailFactory::new();
    }
}
