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
 * @property string | null $slug
 * @property string | null $description
 * @property string | null $timezone
 * @property string | null $url
 * @property bool $is_default
 * @property bool $is_enabled
 * @property array | null $metadata
 */
#[ObservedBy(ChannelObserver::class)]
class Channel extends Model
{
    use HasFactory;
    use HasSlug;

    protected $guarded = [];

    protected $casts = [
        'is_default' => 'boolean',
        'is_enabled' => 'boolean',
        'metadata' => 'array',
    ];

    public function getTable(): string
    {
        return shopper_table('channels');
    }

    protected static function newFactory(): ChannelFactory
    {
        return ChannelFactory::new();
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    public function products(): MorphToMany
    {
        return $this->morphToMany(
            config('shopper.models.product'),
            'productable',
            shopper_table('product_has_relations')
        );
    }
}
