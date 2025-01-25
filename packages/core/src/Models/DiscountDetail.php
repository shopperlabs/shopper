<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Shopper\Core\Database\Factories\DiscountDetailFactory;

/**
 * @property-read int $id
 * @property string $condition
 * @property string $discountable_type
 * @property int $discountable_id
 * @property int $discount_id
 * @property int $total_use
 * @property-read Discount $discount
 */
class DiscountDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('discountables');
    }

    protected static function newFactory(): DiscountDetailFactory
    {
        return DiscountDetailFactory::new();
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    public function discountable(): MorphTo
    {
        return $this->morphTo();
    }
}
