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
 * @property CollectionType $type
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property array|null $metadata
 */
class Collection extends Model implements SpatieHasMedia
{
    use HasFactory;
    use HasMedia;
    use HasSlug;

    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
        'metadata' => 'array',
        'type' => CollectionType::class
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
        return $this->type === CollectionType::AUTO;
    }

    public function isManual(): bool
    {
        return ! $this->isAutomatic();
    }

    public function firstRule(): ?string
    {
        $condition = $this->rules()->first();

        if ($this->isAutomatic()) {
            $words = $condition->getFormattedRule() . ' ' . $condition->getFormattedOperator() . ' ' . $condition->getFormattedValue();
            $rules = $this->rules()->count();

            return $words . ' ' . ($rules >= 2 ? '+ '. $rules - 1 . __('shopper::words.other') : '');
        }

        return null;
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
