<?php

namespace Shopper\Framework\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Traits\Mediatable;

class Collection extends Model
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
        'published_at',
        'type',
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

        static::created(function ($collection) {
            $collection->update(['slug' => $collection->name]);
        });
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return shopper_table('collections');
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
}
