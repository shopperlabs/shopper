<?php

namespace Shopper\Framework\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
      'inventory'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
      'adjustment'
    ];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return shopper_table('inventory_histories');
    }

    /**
     * Get the inventory history adjustment.
     *
     * @return string
     */
    public function getAdjustmentAttribute()
    {
        if ($this->old_quantity > 0) {
            return '+'. $this->old_quantity;
        }

        return $this->old_quantity;
    }

    /**
     * Get the inventory of the current history.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    /**
     * Get the user who adjusted the history quantity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model', User::class), 'user_id');
    }

    /**
     * Relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function stockable()
    {
        return $this->morphTo();
    }

    /**
     * Relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function reference()
    {
        return $this->morphTo();
    }
}
