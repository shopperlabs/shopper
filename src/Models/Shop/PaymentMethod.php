<?php

namespace Shopper\Framework\Models\Shop;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'logo',
        'link_url',
        'description',
        'instructions',
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
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->update(['slug' => $model->title]);
        });
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return shopper_table('payment_methods');
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
     * Scope a query to only include enabled categories.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled(Builder $query)
    {
        return $query->where('is_enabled', true);
    }
}
