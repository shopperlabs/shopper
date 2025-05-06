<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Database\Factories\ChannelFactory;
use Shopper\Core\Observers\ChannelObserver;
use Shopper\Core\Traits\HasSlug;

/**
 * @property-read int $id
 * @property string $name
 * @property string|null $slug
 * @property string|null $description
 * @property string|null $timezone
 * @property string|null $url
 * @property bool $is_default
 * @property bool $is_enabled
 * @property array<array-key, mixed>|null $metadata
 * @property-read \Illuminate\Support\Collection<int, Product> $products
 */
#[ObservedBy(ChannelObserver::class)]
class Channel extends Model
{
    /** @use HasFactory<ChannelFactory> */
    use HasFactory;

    use HasSlug;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('channels');
    }

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'is_enabled' => 'boolean',
            'metadata' => 'array',
        ];
    }

    protected static function newFactory(): ChannelFactory
    {
        return ChannelFactory::new();
    }

    /**
     * @param  Builder<Channel>  $query
     * @return Builder<Channel>
     */
    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    /**
     * @param  Builder<Channel>  $query
     * @return Builder<Channel>
     */
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    /**
     * @return MorphToMany<Product, $this>
     */
    public function products(): MorphToMany
    {
        // @phpstan-ignore-next-line
        return $this->morphToMany(
            config('shopper.models.product'),
            'productable',
            shopper_table('product_has_relations')
        );
    }
}
