<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Casts\Attribute as LaravelAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Core\Database\Factories\AttributeProductFactory;
use Shopper\Core\Models\Contracts\AttributeProduct as AttributeProductContract;

/**
 * @property-read int $id
 * @property-read int $attribute_id
 * @property-read int $product_id
 * @property-read ?string $attribute_custom_value
 * @property-read ?int $attribute_value_id
 * @property-read ?Contracts\AttributeValue $value
 * @property-read string $real_value
 * @property-read Product $product
 * @property-read Attribute $attribute
 */
class AttributeProduct extends Model implements AttributeProductContract
{
    /** @use HasFactory<AttributeProductFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('attribute_product');
    }

    /**
     * @return BelongsTo<Attribute, $this>
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        // @phpstan-ignore-next-line
        return $this->belongsTo(config('shopper.models.product'), 'product_id');
    }

    /**
     * @return BelongsTo<AttributeValue, $this>
     */
    public function value(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }

    protected static function newFactory(): AttributeProductFactory
    {
        return AttributeProductFactory::new();
    }

    protected function realValue(): LaravelAttribute
    {
        return LaravelAttribute::get(fn (): string => $this->attribute_custom_value ?? $this->value?->value);
    }
}
