<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class DiscountDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'discountable_type',
        'discountable_id',
        'condition',
        'discount_id',
    ];

    public function getTable(): string
    {
        return shopper_table('discountables');
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
