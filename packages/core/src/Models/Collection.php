<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Traits\HasMedia;
use Shopper\Core\Traits\HasSlug;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

class Collection extends Model implements SpatieHasMedia
{
    use HasFactory;
    use HasSlug;
    use HasMedia;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function getTable(): string
    {
        return shopper_table('collections');
    }

    public function scopeManual(Builder $query): Builder
    {
        return $query->where('type', 'manual');
    }

    public function scopeAutomatic(Builder $query): Builder
    {
        return $query->where('type', 'auto');
    }

    public function isAutomatic(): bool
    {
        return $this->type === 'auto';
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
