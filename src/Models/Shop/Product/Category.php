<?php

namespace Shopper\Framework\Models\Shop\Product;

use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Models\Traits\Filetable;

class Category extends Model
{
    use Filetable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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
    protected $with = ['parent'];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->update(['slug' => $model->name]);
        });
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return shopper_table('categories');
    }

    /**
     * Set the proper slug attribute.
     *
     * @param  string  $value
     */
    public function setSlugAttribute($value)
    {
        if (static::query()->where('slug', $slug = str_slug($value))->exists()) {
            $slug = "{$slug}-{$this->id}";
        }

        $this->attributes['slug'] = $slug;
    }

    /**
     * Get Parent name.
     *
     * @return string
     */
    public function getParentNameAttribute()
    {
        if ($this->parent_id !== null) {
            return $this->parent->name;
        }

        return __('N/A (No parent category)');
    }

    /**
     * Get all categories childs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childs()
    {
        return $this->hasMany(self::class);
    }

    /**
     * Get Category parent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Return products associated to the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(config('shopper.config.models.product'), shopper_table('category_product'), 'category_id');
    }
}
