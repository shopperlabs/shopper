<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Shopper\Core\Contracts\Priceable;
use Shopper\Core\Database\Factories\ProductVariantFactory;
use Shopper\Core\Enum\Dimension\Length;
use Shopper\Core\Enum\Dimension\Volume;
use Shopper\Core\Enum\Dimension\Weight;
use Shopper\Core\Models\Contracts\ProductVariant as ProductVariantContract;
use Shopper\Core\Models\Traits\HasDimensions;
use Shopper\Core\Models\Traits\HasMedia;
use Shopper\Core\Models\Traits\HasPrices;
use Shopper\Core\Models\Traits\HasStock;
use Shopper\Core\Traits\HasModelContract;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read ?string $sku
 * @property-read ?string $barcode
 * @property-read ?string $ean
 * @property-read ?string $upc
 * @property-read Weight $weight_unit
 * @property-read ?float $weight_value
 * @property-read Length $height_unit
 * @property-read ?float $height_value
 * @property-read Length $width_unit
 * @property-read ?float $width_value
 * @property-read Length $depth_unit
 * @property-read ?float $depth_value
 * @property-read Volume $volume_unit
 * @property-read ?float $volume_value
 * @property-read bool $allow_backorder
 * @property-read int $position
 * @property-read int $product_id
 * @property-read array<array-key, mixed>|null $metadata
 * @property-read int $stock
 * @property-read Contracts\Product $product
 * @property-read Collection<int, AttributeValue> $values
 *
 * @implements Priceable<ProductVariant>
 */
class ProductVariant extends Model implements Priceable, ProductVariantContract, SpatieHasMedia
{
    use HasDimensions;

    /** @use HasFactory<ProductVariantFactory> */
    use HasFactory;

    use HasMedia;
    use HasModelContract;
    use HasPrices;
    use HasStock;

    protected $guarded = [];

    public static function configKey(): string
    {
        return 'variant';
    }

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
