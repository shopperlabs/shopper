<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Shopper\Core\Database\Factories\AttributeValueFactory;

/**
 * @property-read int $id
 * @property string $value
 * @property string $key
 * @property int $position
 * @property int $attribute_id
 * @property-read Attribute $attribute
 * @property-read \Illuminate\Database\Eloquent\Collection | ProductVariant[] $variants
 */
class AttributeValue extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('attribute_values');
    }

    protected static function newFactory(): AttributeValueFactory
    {
        return AttributeValueFactory::new();
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
        // @phpstan-ignore-next-line
        return $this->belongsToMany(
            config('shopper.models.variant'),
            shopper_table('attribute_value_product_variant'),
            'value_id',
            'variant_id'
        );
    }
}
