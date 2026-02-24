<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Shopper\Core\Database\Factories\AttributeValueFactory;
use Shopper\Core\Models\Contracts\AttributeValue as AttributeValueContract;

/**
 * @property-read int $id
 * @property-read string $value
 * @property-read string $key
 * @property-read int $position
 * @property-read int $attribute_id
 * @property-read Attribute $attribute
 * @property-read Collection<int, ProductVariant> $variants
 */
class AttributeValue extends Model implements AttributeValueContract
{
    /** @use HasFactory<AttributeValueFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('attribute_values');
    }

    /**
     * @return BelongsTo<Attribute, $this>
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    /**
     * @return BelongsTo<AttributeProduct, $this>
     */
    public function attributeProduct(): BelongsTo
    {
        return $this->belongsTo(AttributeProduct::class, 'attribute_value_id');
    }

    /**
     * @return BelongsToMany<ProductVariant, $this>
     */
    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(
            config('shopper.models.variant'),
            shopper_table('attribute_value_product_variant'),
            'value_id',
            'variant_id'
        );
    }

    protected static function newFactory(): AttributeValueFactory
    {
        return AttributeValueFactory::new();
    }
}
