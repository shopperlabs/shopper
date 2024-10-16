<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property-read int $id
 * @property int $quantity
 * @property int | null $old_quantity
 * @property string | null $event
 * @property string | null $description
 * @property int $user_id
 * @property int $inventory_id
 * @property string | int $adjustment
 */
class InventoryHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'stockable_type',
        'stockable_id',
        'reference_type',
        'reference_id',
        'inventory_id',
        'user_id',
        'event',
        'quantity',
        'old_quantity',
        'description',
    ];

    public function getTable(): string
    {
        return shopper_table('inventory_histories');
    }

    protected function adjustment(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->old_quantity > 0
                ? '+' . $this->old_quantity
                : $this->old_quantity
        );
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
