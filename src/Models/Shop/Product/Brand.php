<?php

namespace Shopper\Framework\Models\Shop\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Shopper\Framework\Models\Traits\Filetable;

class Brand extends Model
{
    use Filetable;

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
     * The relations to eager load on every query.
     *
     * @var array
     */
    // protected $with = ['files']; // Be careful using the $with magic. It can quickly bloat the SQL calls. Try Laravel Debugbar. This was loaded once for every product on the browse page, but wasn't even used.

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return shopper_table('brands');
    }

    /**
     * Scope a query to only include enabled brands.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled(Builder $query)
    {
        return $query->where('is_enabled', true);
    }

    /**
     * Return products associated to the brand.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(config('shopper.system.models.product'));
    }
}
