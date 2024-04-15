<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property string $rule
 * @property string $operator
 * @property string $value
 * @property int $collection_id
 * @property Collection $collection
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
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
            return shopper_money_format((int) $this->value);
        }

        return $this->value;
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(config('shopper.models.collection'), 'collection_id');
    }
}
