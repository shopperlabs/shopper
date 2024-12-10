<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Contracts\ReviewRateable;
use Shopper\Core\Database\Factories\ProductFactory;
use Shopper\Core\Enum\Dimension\Length;
use Shopper\Core\Enum\Dimension\Volume;
use Shopper\Core\Enum\Dimension\Weight;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Traits\CanHaveDiscount;
use Shopper\Core\Traits\HasMedia;
use Shopper\Core\Traits\HasSlug;
use Shopper\Core\Traits\HasStock;
use Shopper\Core\Traits\ReviewRateable as ReviewRateableTrait;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

/**
 * @property-read int $id
 * @property string $name
 * @property string $slug
 * @property string | null $sku
 * @property string | null $barcode
 * @property ProductType | null $type
 * @property bool $is_visible
 * @property bool $featured
 * @property Weight $weight_unit
 * @property float| null $weight_value
 * @property Length $height_unit
 * @property float | null $height_value
 * @property Length $width_unit
 * @property float| null $width_value
 * @property Length $depth_unit
 * @property float| null $depth_value
 * @property Volume $volume_unit
 * @property float| null $volume_value
 * @property int | null $security_stock
 * @property int $variants_stock
 * @property string | null $seo_title
 * @property string | null $seo_description
 * @property \Carbon\Carbon | null $published_at
 * @property array | null $metadata
 * @property-read int | null $stock
 */
class Product extends Model implements ReviewRateable, SpatieHasMedia
{
    use CanHaveDiscount;
    use HasFactory;
    use HasMedia;
    use HasSlug;
    use HasStock;
    use ReviewRateableTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'featured' => 'boolean',
        'is_visible' => 'boolean',
        'published_at' => 'datetime',
        'metadata' => 'array',
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
        'type' => ProductType::class,
    ];

    public function getTable(): string
    {
        return shopper_table('products');
    }

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

    public function variantsStock(): Attribute
    {
        $stock = 0;

        if ($this->variants->isNotEmpty()) {
            /** @var ProductVariant $variant */
            foreach ($this->variants as $variant) {
                $stock += $variant->stock;
            }
        }

        return Attribute::get(fn () => $stock);
    }

    public function scopePublish(Builder $query): void
    {
        $query->whereDate('published_at', '<=', now())
            ->where('is_visible', true);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(config('shopper.models.variant'), 'product_id');
    }

    public function channels(): MorphToMany
    {
        return $this->morphedByMany(config('shopper.models.channel'), 'productable', shopper_table('product_has_relations'));
    }

    public function relatedProducts(): MorphToMany
    {
        return $this->morphedByMany(config('shopper.models.product'), 'productable', shopper_table('product_has_relations'));
    }

    public function categories(): MorphToMany
    {
        return $this->morphedByMany(config('shopper.models.category'), 'productable', shopper_table('product_has_relations'));
    }

    public function collections(): MorphToMany
    {
        return $this->morphedByMany(config('shopper.models.collection'), 'productable', shopper_table('product_has_relations'));
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(config('shopper.models.brand'), 'brand_id');
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(AttributeProduct::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(config('shopper.media.storage.collection_name'))
            ->useDisk(config('shopper.media.storage.disk_name'))
            ->acceptsMimeTypes(config('shopper.media.accepts_mime_types'))
            ->useFallbackUrl(shopper_fallback_url());

        $this->addMediaCollection(config('shopper.media.storage.thumbnail_collection'))
            ->singleFile()
            ->useDisk(config('shopper.media.storage.disk_name'))
            ->acceptsMimeTypes(config('shopper.media.accepts_mime_types'))
            ->useFallbackUrl(shopper_fallback_url());

        $this->addMediaCollection('downloadable')
            ->useDisk(config('shopper.media.storage.disk_name'));
    }
}
