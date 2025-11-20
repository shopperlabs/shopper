<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Shopper\Contracts\Priceable;
use Shopper\Core\Database\Factories\ProductVariantFactory;
use Shopper\Core\Enum\Dimension\Length;
use Shopper\Core\Enum\Dimension\Volume;
use Shopper\Core\Enum\Dimension\Weight;
use Shopper\Core\Models\Traits\HasDimensions;
use Shopper\Core\Models\Traits\HasMedia;
use Shopper\Core\Models\Traits\HasPrices;
use Shopper\Core\Models\Traits\HasStock;
use Shopper\Core\Observers\ProductVariantObserver;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string|null $sku
 * @property-read string|null $barcode
 * @property-read string|null $ean
 * @property-read string|null $upc
 * @property-read Weight $weight_unit
 * @property-read float|null $weight_value
 * @property-read Length $height_unit
 * @property-read float|null $height_value
 * @property-read Length $width_unit
 * @property-read float|null $width_value
 * @property-read Length $depth_unit
 * @property-read float|null $depth_value
 * @property-read Volume $volume_unit
 * @property-read float|null $volume_value
 * @property-read bool $allow_backorder
 * @property-read int $position
 * @property-read int $product_id
 * @property-read array<array-key, mixed>|null $metadata
 * @property-read int $stock
 * @property-read Product $product
 * @property-read \Illuminate\Support\Collection<int, AttributeValue> $values
 *
 * @implements Priceable<ProductVariant>
 */
#[ObservedBy(ProductVariantObserver::class)]
class ProductVariant extends Model implements Priceable, SpatieHasMedia
{
    use HasDimensions;

    /** @use HasFactory<ProductVariantFactory> */
    use HasFactory;

    use HasMedia;
    use HasPrices;
    use HasStock;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('product_variants');
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
     * @return BelongsToMany<AttributeValue, $this>
     */
    public function values(): BelongsToMany
    {
        return $this->belongsToMany(
            AttributeValue::class,
            shopper_table('attribute_value_product_variant'),
            'variant_id',
            'value_id'
        );
    }

    protected static function newFactory(): ProductVariantFactory
    {
        return ProductVariantFactory::new();
    }

    protected function casts(): array
    {
        return [
            'allow_backorder' => 'boolean',
            'metadata' => 'array',
            'position' => 'integer',
            'weight_unit' => Weight::class,
            'weight_value' => 'decimal:2',
            'width_unit' => Length::class,
            'width_value' => 'decimal:2',
            'height_unit' => Length::class,
            'height_value' => 'decimal:2',
            'depth_unit' => Length::class,
            'depth_value' => 'decimal:2',
            'volume_unit' => Volume::class,
            'volume_value' => 'decimal:2',
        ];
    }
}
