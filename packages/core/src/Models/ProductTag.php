<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Database\Factories\ProductTagFactory;
use Shopper\Core\Models\Traits\HasSlug;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read ?string $slug
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $products
 */
class ProductTag extends Model
{
    /** @use HasFactory<ProductTagFactory> */
    use HasFactory;

    use HasSlug;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('product_tags');
    }

    /**
     * @return MorphToMany<Product, $this>
     */
    public function products(): MorphToMany
    {
        // @phpstan-ignore-next-line
        return $this->morphToMany(config('shopper.models.product'), 'productable', shopper_table('product_has_relations'));
    }

    protected static function newFactory(): ProductTagFactory
    {
        return ProductTagFactory::new();
    }
}
