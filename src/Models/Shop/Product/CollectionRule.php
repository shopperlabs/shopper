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
            'product_title' => __('shopper::pages/collections.rules.product_title'),
            'product_brand' => __('shopper::pages/collections.rules.product_brand'),
            'product_category' => __('shopper::pages/collections.rules.product_category'),
            'product_price' => __('shopper::pages/collections.rules.product_price'),
            'compare_at_price' => __('shopper::pages/collections.rules.compare_at_price'),
            'inventory_stock' => __('shopper::pages/collections.rules.inventory_stock'),
        ][$this->rule];
    }

    public function getFormattedOperator(): string
    {
        return [
            'equals_to' => __('shopper::pages/collections.operator.equals_to'),
            'not_equals_to' => __('shopper::pages/collections.operator.not_equals_to'),
            'less_than' => __('shopper::pages/collections.operator.less_than'),
            'greater_than' => __('shopper::pages/collections.operator.greater_than'),
            'starts_with' => __('shopper::pages/collections.operator.starts_with'),
            'ends_with' => __('shopper::pages/collections.operator.ends_with'),
            'contains' => __('shopper::pages/collections.operator.contains'),
            'not_contains' => __('shopper::pages/collections.operator.not_contains'),
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
