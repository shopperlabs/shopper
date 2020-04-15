<?php

namespace Shopper\Framework\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Traits\Mediatable;

class Brand extends Model
{
    use Mediatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['previewImage'];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($brand) {
            $brand->update(['slug' => $brand->name]);
        });
    }

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
     * Set the proper slug attribute.
     *
     * @param  string $value
     */
    public function setSlugAttribute($value)
    {
        if (static::where('slug', $slug = str_slug($value))->exists()) {
            $slug = "{$slug}-{$this->id}";
        }

        $this->attributes['slug'] = $slug;
    }

    /**
     * Return products associated to the brand.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(config('shopper.models.product'));
    }
}
