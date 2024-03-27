<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Enum\CollectionType;
use Shopper\Core\Traits\HasMedia;
use Shopper\Core\Traits\HasSlug;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

/**
 * @property-read int $id
 * @property string $type
 * @property string $name
 * @property string $slug
 * @property string|null $description
 */
class Collection extends Model implements SpatieHasMedia
{
    use HasFactory;
    use HasMedia;
    use HasSlug;

    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function getTable(): string
    {
        return shopper_table('collections');
    }

    public function scopeManual(Builder $query): Builder
    {
        return $query->where('type', CollectionType::MANUAL->value);
    }

    public function scopeAutomatic(Builder $query): Builder
    {
        return $query->where('type', CollectionType::AUTO->value);
    }

    public function isAutomatic(): bool
    {
        return $this->type === CollectionType::AUTO->value;
    }

    public function isManual(): bool
    {
        return ! $this->isAutomatic();
    }

    public function firstRule(): string
    {
        $condition = $this->rules()->first();

        return $condition->getFormattedRule() . ' ' . $condition->getFormattedOperator() . ' ' . $condition->getFormattedValue();
    }

    public function products(): MorphToMany
    {
        return $this->morphToMany(config('shopper.models.product'), 'productable', 'product_has_relations');
    }

    public function rules(): HasMany
    {
        return $this->hasMany(CollectionRule::class, 'collection_id');
    }
}
