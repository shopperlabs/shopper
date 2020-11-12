<?php

namespace Shopper\Framework\Models\Shop\Product;

use Illuminate\Database\Eloquent\Model;

class CollectionRule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rule',
        'operator',
        'value',
        'collection_id',
    ];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return shopper_table('collection_rules');
    }

    /**
     * Return the formatted words for a rule.
     *
     * @return mixed
     */
    public function getFormattedRule()
    {
        return [
            'product_title' => __('Product title'),
            'product_brand' => __('Product brand'),
            'product_category' => __('Product category'),
            'product_price' => __('Product price'),
            'compare_at_price' => __('Compare at price'),
            'inventory_stock' => __('Inventory stock'),
        ][$this->rule];
    }

    /**
     * Return the formatted words for an operator.
     *
     * @return mixed
     */
    public function getFormattedOperator()
    {
        return [
            'equals_to' => __('Equals to'),
            'not_equals_to' => __('Not equals to'),
            'less_than' => __('Less than'),
            'greater_than' => __('Greater than'),
            'starts_with' => __('Starts with'),
            'ends_with' => __('End with'),
            'contains' => __('Contains'),
            'not_contains' => __('Not contains'),
        ][$this->operator];
    }

    /**
     * Return collection related to the current collection rule.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function collection()
    {
        return $this->belongsTo(config('shopper.system.models.collection'), 'collection_id');
    }
}
