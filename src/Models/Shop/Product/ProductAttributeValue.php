<?php

namespace Shopper\Framework\Models\Shop\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttributeValue extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_attribute_id',
        'attribute_value_id',
        'product_custom_value',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['value'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['real_value'];

    /**
     * Return exact product attribute value.
     */
    public function getRealValueAttribute()
    {
        if ($this->product_custom_value) {
            return $this->product_custom_value;
        }

        return $this->value->value;
    }

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return shopper_table('attribute_value_product_attribute');
    }

    public function value(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }
}
