<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Casts\Attribute as LaravelAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Core\Database\Factories\AttributeProductFactory;

/**
 * @property-read int $id
 * @property int $attribute_id
 * @property int $product_id
 * @property string | null $attribute_custom_value
 * @property int | null $attribute_value_id
 * @property AttributeValue | null $value
 * @property-read string $real_value
 * @property Product $product
 * @property Attribute $attribute
 */
class AttributeProduct extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('attribute_product');
    }

    protected static function newFactory(): AttributeProductFactory
    {
        return AttributeProductFactory::new();
    }

    protected function realValue(): LaravelAttribute
    {
        return LaravelAttribute::get(fn (): string => $this->attribute_custom_value ?? $this->value?->value);
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
