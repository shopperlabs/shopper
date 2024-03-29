<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Traits\HasMedia;
use Shopper\Core\Traits\HasSlug;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

/**
 * @property-read int $id
 * @property string $name
 * @property string | null $slug
 * @property string | null $website
 * @property string | null $description
 * @property int $position
 * @property bool $is_enabled
 * @property string | null $seo_title
 * @property string | null $seo_description
 * @property array $metadata
 */
class Brand extends Model implements SpatieHasMedia
{
    use HasFactory;
    use HasMedia;
    use HasSlug;

    protected $guarded = [];

    protected $casts = [
        'is_enabled' => 'boolean',
        'metadata' => 'array',
    ];

    public function getTable(): string
    {
        return shopper_table('brands');
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    public function products(): HasMany
    {
        return $this->hasMany(config('shopper.models.product'));
    }

    public function updateStatus(bool $status = true): void
    {
        $this->is_enabled = $status;

        $this->save();
    }
}
