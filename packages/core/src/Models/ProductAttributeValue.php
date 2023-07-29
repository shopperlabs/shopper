<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'attribute_value_id',
        'attribute_custom_value',
        'product_id',
    ];

    public $timestamps = false;

    protected $with = [
        'value',
    ];

    protected $appends = [
        'real_value',
    ];

    public function getTable(): string
    {
        return shopper_table('attribute_value_product_attribute');
    }

    public function getRealValueAttribute(): string
    {
        // @phpstan-ignore-next-line
        if ($this->product_custom_value) {
            return $this->product_custom_value;
        }

        return $this->value->value;
    }

    public function value(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }
}
