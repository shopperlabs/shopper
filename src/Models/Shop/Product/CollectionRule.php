<?php

namespace Shopper\Framework\Models\Shop\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionRule extends Model
{
    use HasFactory;

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
     */
    public function getTable(): string
    {
        return shopper_table('collection_rules');
    }

    public function getFormattedRule(): string
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

    public function getFormattedOperator(): string
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

    public function getFormattedValue(): string
    {
        if ($this->rule === 'product_price') {
            return shopper_money_format(strtoupper($this->value));
        }

        return $this->value;
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(config('shopper.system.models.collection'), 'collection_id');
    }
}
