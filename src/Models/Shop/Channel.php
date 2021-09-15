<?php

namespace Shopper\Framework\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Channel extends Model
{
    use HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'timezone',
        'url',
        'is_default',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return shopper_table('channels');
    }

    public function products(): MorphToMany
    {
        return $this->morphToMany(config('shopper.system.models.product'), 'productable', 'product_has_relations');
    }
}
