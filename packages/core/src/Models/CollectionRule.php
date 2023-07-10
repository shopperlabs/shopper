<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionRule extends Model
{
    use HasFactory;

    protected $guarded = [];

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
        ][$this->rule]; // @phpstan-ignore-line
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
        ][$this->operator]; // @phpstan-ignore-line
    }

    public function getFormattedValue(): string
    {
        // @phpstan-ignore-next-line
        if ($this->rule === 'product_price') {
            // @phpstan-ignore-next-line
            return shopper_money_format(strtoupper($this->value));
        }

        return $this->value; // @phpstan-ignore-line
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(config('shopper.models.collection'), 'collection_id');
    }
}
