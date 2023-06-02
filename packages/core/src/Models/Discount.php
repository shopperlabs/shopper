<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discount extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'usage_limit_per_user' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function getTable(): string
    {
        return shopper_table('discounts');
    }

    public function hasReachedLimit(): bool
    {
        if (null !== $this->usage_limit) {
            return $this->total_use === $this->usage_limit;
        }

        return false;
    }

    public function items(): HasMany
    {
        return $this->hasMany(DiscountDetail::class, 'discount_id');
    }
}
