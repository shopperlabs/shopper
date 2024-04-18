<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property string $code
 * @property string $type
 * @property int $value
 * @property string $apply_to
 * @property string $eligibility
 * @property int $usage_limit
 * @property int $total_use
 * @property bool $usage_limit_per_user
 * @property bool $is_active
 * @property array $metadata
 * @property \Illuminate\Support\Carbon $start_at
 * @property \Illuminate\Support\Carbon|null $end_at
 */
class Discount extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'usage_limit_per_user' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function getTable(): string
    {
        return shopper_table('discounts');
    }

    public function hasReachedLimit(): bool
    {
        if ($this->usage_limit !== null) {
            return $this->total_use === $this->usage_limit;
        }

        return false;
    }

    public function items(): HasMany
    {
        return $this->hasMany(DiscountDetail::class, 'discount_id');
    }
}
