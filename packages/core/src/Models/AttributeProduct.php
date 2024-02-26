<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Casts\Attribute as AttributeCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property string|null $attribute_custom_value
 * @property AttributeValue|null $value
 */
class AttributeProduct extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'attribute_id',
        'attribute_value_id',
        'attribute_custom_value',
        'product_id',
    ];

    public function getTable(): string
    {
        return shopper_table('attribute_product');
    }

    protected function realValue(): AttributeCast
    {
        return AttributeCast::make(
            get: fn () => $this->attribute_custom_value ?? $this->value?->value,
        );
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(config('shopper.models.product'), 'product_id');
    }

    public function value(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }
}
