<?php

namespace Shopper\Framework\Models\Shop\Product;

use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Models\Traits\HasSlug;
use Shopper\Framework\Models\Traits\Filetable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Collection extends Model
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
        'description',
        'published_at',
        'type',
        'sort',
        'match_conditions',
        'seo_title',
        'seo_description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return shopper_table('collections');
    }

    /**
     * Return the correct formatted word of the first collection rule.
     */
    public function firstRule(): string
    {
        $condition = $this->rules()->first();

        return $condition->getFormattedRule() . ' ' . $condition->getFormattedOperator() . ' ' . $condition->value;
    }

    public function products(): MorphToMany
    {
        return $this->morphToMany(config('shopper.system.models.product'), 'productable', 'product_has_relations');
    }

    public function rules(): HasMany
    {
        return $this->hasMany(CollectionRule::class, 'collection_id');
    }
}
