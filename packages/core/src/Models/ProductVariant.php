<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
 * @property string $name
 * @property string|null $sku
 * @property string|null $barcode
 * @property string|null $ean
 * @property string|null $upc
 * @property Weight $weight_unit
 * @property float|null $weight_value
 * @property Length $height_unit
 * @property float|null $height_value
 * @property Length $width_unit
 * @property float|null $width_value
 * @property Length $depth_unit
 * @property float|null $depth_value
 * @property Volume $volume_unit
 * @property float|null $volume_value
 * @property bool $allow_backorder
 * @property int $position
 * @property int $product_id
 * @property array<array-key, mixed>|null $metadata
 * @property-read int $stock
 * @property-read Product $product
 * @property-read \Illuminate\Database\Eloquent\Collection | Price[] $prices
 * @property-read \Illuminate\Database\Eloquent\Collection | AttributeValue[] $values
 */
#[ObservedBy(ProductVariantObserver::class)]
class ProductVariant extends Model implements SpatieHasMedia
{
    /** @use HasFactory<ProductVariantFactory> */
    use HasFactory;

    use HasDimensions;
    use HasMedia;
    use HasPrices;
    use HasStock;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('product_variants');
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

    protected static function newFactory(): ProductVariantFactory
    {
        return ProductVariantFactory::new();
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
}
