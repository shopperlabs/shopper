<?php

namespace Shopper\Framework\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'discountable_type',
        'discountable_id',
        'condition',
        'discount_id',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['discount'];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return shopper_table('discountables');
    }

    /**
     * Get the discount associated with this content.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    /**
     * Morph Relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function discountable()
    {
        return $this->morphTo();
    }
}