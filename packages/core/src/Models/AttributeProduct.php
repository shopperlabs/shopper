<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Casts\Attribute as LaravelAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Core\Contracts\Media\HasMedia as ShopperHasMedia;
use Shopper\Core\Database\Factories\AttributeProductFactory;
use Shopper\Core\Media\MediaCollectionConfig;
use Shopper\Core\Models\Contracts\AttributeProduct as AttributeProductContract;
use Shopper\Core\Traits\HasModelContract;

/**
 * @property-read int $id
 * @property-read int $attribute_id
 * @property-read int $product_id
 * @property-read ?string $attribute_custom_value
 * @property-read ?int $attribute_value_id
 * @property-read ?AttributeValue $value
 * @property-read string $real_value
 * @property-read Product $product
 * @property-read Attribute $attribute
 */
class AttributeProduct extends Model implements AttributeProductContract, ShopperHasMedia
{
    /** @use HasFactory<AttributeProductFactory> */
    use HasFactory;

    use HasModelContract;

    public $timestamps = false;

    protected $guarded = [];

    public static function configuredClass(): string
    {
        return config('shopper.models.attribute_product', static::class);
    }

    public function getTable(): string
    {
        return shopper_table('attribute_product');
    }

    /** @return array<string, MediaCollectionConfig> */
    public function getMediaCollections(): array
    {
        return [
            'swatch' => new MediaCollectionConfig(
                name: 'swatch',
                disk: config('shopper.media.storage.disk_name', 'public'),
                singleFile: true,
                acceptsMimeTypes: ['image/jpeg', 'image/png', 'image/webp', 'image/avif'],
            ),
        ];
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
