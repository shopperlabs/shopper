<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Traits\HasSlug;
use Shopper\Core\Traits\HasZones;

/**
 * @property-read int $id
 * @property string $name
 * @property bool $is_enabled
 * @property string | null $slug
 * @property string | null $logo
 * @property string | null $link_url
 * @property string | null $description
 * @property int | null $shipping_amount
 * @property array $metadata
 */
class Carrier extends Model
{
    use HasFactory;
    use HasSlug;
    use HasZones;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'link_url',
        'description',
        'shipping_amount',
        'is_enabled',
        'metadata',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'metadata' => 'array',
    ];

    public function getTable(): string
    {
        return shopper_table('carriers');
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    public function options(): HasMany
    {
        return $this->hasMany(CarrierOption::class);
    }
}
