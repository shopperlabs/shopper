<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Database\Factories\ChannelFactory;
use Shopper\Core\Models\Contracts\Channel as ChannelContract;
use Shopper\Core\Models\Traits\HasSlug;
use Shopper\Core\Traits\HasModelContract;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read ?string $slug
 * @property-read ?string $description
 * @property-read ?string $timezone
 * @property-read ?string $url
 * @property-read bool $is_default
 * @property-read bool $is_enabled
 * @property-read array<string, mixed>|null $metadata
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read Collection<int, Product> $products
 */
class Channel extends Model implements ChannelContract
{
    /** @use HasFactory<ChannelFactory> */
    use HasFactory;

    use HasModelContract;
    use HasSlug;

    protected $guarded = [];

    public static function configKey(): string
    {
        return 'channel';
    }

    public function getTable(): string
    {
        return shopper_table('channels');
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

    protected static function newFactory(): ChannelFactory
    {
        return ChannelFactory::new();
    }

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'is_enabled' => 'boolean',
            'metadata' => 'array',
        ];
    }
}
