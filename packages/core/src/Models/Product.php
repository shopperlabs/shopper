<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute as LaravelAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Shopper\Core\Contracts\HasReviews;
use Shopper\Core\Contracts\Priceable;
use Shopper\Core\Database\Factories\ProductFactory;
use Shopper\Core\Enum\Dimension\Length;
use Shopper\Core\Enum\Dimension\Volume;
use Shopper\Core\Enum\Dimension\Weight;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Contracts\Product as ProductContract;
use Shopper\Core\Models\Traits\HasDimensions;
use Shopper\Core\Models\Traits\HasDiscounts;
use Shopper\Core\Models\Traits\HasMedia;
use Shopper\Core\Models\Traits\HasPrices;
use Shopper\Core\Models\Traits\HasSlug;
use Shopper\Core\Models\Traits\HasStock;
use Shopper\Core\Models\Traits\InteractsWithReviews;
use Shopper\Core\Traits\HasModelContract;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $slug
 * @property-read ?string $sku
 * @property-read ?string $description
 * @property-read ?string $summary
 * @property-read ?int $brand_id
 * @property-read ?string $barcode
 * @property-read ?ProductType $type
 * @property-read bool $is_visible
 * @property-read bool $featured
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
 * @property-read ?int $security_stock
 * @property-read int $variants_stock
 * @property-read ?string $seo_title
 * @property-read ?string $seo_description
 * @property-read ?string $external_id
 * @property-read ?int $supplier_id
 * @property-read array<string, mixed>|null $metadata
 * @property-read ?CarbonInterface $published_at
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read ?CarbonInterface $deleted_at
 * @property-read int $stock
 * @property-read ?Supplier $supplier
 * @property-read Brand $brand
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Channel> $channels
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $categories
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Attribute> $options
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Collection> $collections
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ProductVariant> $variants
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $relatedProducts
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ProductTag> $tags
 *
 * @implements Priceable<Product>
 */
class Product extends Model implements HasReviews, Priceable, ProductContract, SpatieHasMedia
{
    use HasDimensions;
    use HasDiscounts;

    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    use HasMedia;
    use HasModelContract;
    use HasPrices;
    use HasSlug;
    use HasStock;
    use InteractsWithReviews;
    use SoftDeletes;

    protected $guarded = [];

    public static function configKey(): string
    {
        return 'product';
    }

    public function getTable(): string
    {
        return shopper_table('products');
    }

    public function canUseShipping(): bool
    {
        return $this->isStandard() || $this->isVariant();
    }

    public function canUseAttributes(): bool
    {
        return $this->isStandard() || $this->isVariant();
    }

    public function canUseVariants(): bool
    {
        return $this->isVariant();
    }

    public function isExternal(): bool
    {
        return $this->type === ProductType::External;
    }

    public function isVariant(): bool
    {
        return $this->type === ProductType::Variant;
    }

    public function isVirtual(): bool
    {
        return $this->type === ProductType::Virtual;
    }

    public function isStandard(): bool
    {
        return $this->type === ProductType::Standard;
    }

    public function isPublished(): bool
    {
        return $this->is_visible && $this->published_at && $this->published_at <= now();
    }

    /**
     * @param  Builder<Product>  $query
     */
    public function scopePublish(Builder $query): void
    {
        $query->whereDate('published_at', '<=', now())
            ->where('is_visible', true);
    }

    /**
     * @param  Builder<Product>  $query
     * @param  string|array<string>  $channel
     * @return Builder<Product>
     */
    public function scopeForChannel(Builder $query, string|array $channel): Builder
    {
        $channels = Arr::wrap($channel);

        return $query->whereHas('channels', function (Builder $query) use ($channels): void {
            $query->whereIn('id', $channels);
        });
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

        $this->addMediaCollection('files')
            ->useDisk(config('shopper.media.storage.disk_name'));
    }

    /**
     * @return HasMany<ProductVariant, $this>
     */
    public function variants(): HasMany
    {
        return $this->hasMany(config('shopper.models.variant'), 'product_id');
    }

    /**
     * @return MorphToMany<Channel, $this>
     */
    public function channels(): MorphToMany
    {
        return $this->morphedByMany(config('shopper.models.channel'), 'productable', shopper_table('product_has_relations'));
    }

    /**
     * @return MorphToMany<self, $this>
     */
    public function relatedProducts(): MorphToMany
    {
        return $this->morphedByMany(config('shopper.models.product'), 'productable', shopper_table('product_has_relations'));
    }

    /**
     * @return MorphToMany<Category, $this>
     */
    public function categories(): MorphToMany
    {
        return $this->morphedByMany(config('shopper.models.category'), 'productable', shopper_table('product_has_relations'));
    }

    /**
     * @return MorphToMany<Collection, $this>
     */
    public function collections(): MorphToMany
    {
        return $this->morphedByMany(config('shopper.models.collection'), 'productable', shopper_table('product_has_relations'));
    }

    /**
     * @return MorphToMany<ProductTag, $this>
     */
    public function tags(): MorphToMany
    {
        return $this->morphedByMany(ProductTag::class, 'productable', shopper_table('product_has_relations'));
    }

    /**
     * @return BelongsTo<Supplier, $this>
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(config('shopper.models.supplier'), 'supplier_id');
    }

    /**
     * @return BelongsTo<Brand, $this>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(config('shopper.models.brand'), 'brand_id');
    }

    /**
     * Product Attributes relation, to avoid collision with Model $attributes
     *
     * @return BelongsToMany<Attribute, $this>
     */
    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, table: shopper_table('attribute_product'))
            ->withPivot([
                'attribute_value_id',
                'attribute_custom_value',
            ]);
    }

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

    protected function variantsStock(): LaravelAttribute
    {
        $stock = 0;

        if ($this->variants->isNotEmpty()) {
            /** @var ProductVariant $variant */
            foreach ($this->variants as $variant) {
                $stock += $variant->stock;
            }
        }

        return LaravelAttribute::get(fn (): int => $stock);
    }

    protected function casts(): array
    {
        return [
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
    }
}
