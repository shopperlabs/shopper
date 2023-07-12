<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InventoryHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'stockable_type',
        'stockable_id',
        'reference_type',
        'reference_id',
        'inventory_id',
        'event',
        'quantity',
        'old_quantity',
        'description',
        'user_id',
    ];

    protected $appends = [
        'adjustment',
    ];

    public function getTable(): string
    {
        return shopper_table('inventory_histories');
    }

    public function getAdjustmentAttribute(): string
    {
        // @phpstan-ignore-next-line
        if ($this->old_quantity > 0) {
            return '+' . $this->old_quantity;
        }

        return (string) $this->old_quantity;
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', User::class), 'user_id');
    }

    public function stockable(): MorphTo
    {
        return $this->morphTo();
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
