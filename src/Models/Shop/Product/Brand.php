<?php

namespace Shopper\Framework\Models\Shop\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Shopper\Framework\Models\Traits\HasSlug;
use Shopper\Framework\Models\Traits\Filetable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use Filetable;
    use HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'website',
        'description',
        'position',
        'seo_title',
        'seo_description',
        'is_enabled',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    /**
     * Get the table associated with the model.
     */
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
        return $this->hasMany(config('shopper.system.models.product'));
    }
}
