<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shopper\Core\Database\Factories\GeolocationFactory;

/**
 * @property-read int $id
 * @property-read int $user_id
 * @property-read int $order_id
 * @property-read array<array-key, mixed>|null $ip_api
 * @property-read array<array-key, mixed>|null $extreme_ip_lookup
 * @property-read \Illuminate\Foundation\Auth\User|User $user
 * @property-read Order $order
 */
class Geolocation extends Model
{
    /** @use HasFactory<GeolocationFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('users_geolocation_history');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        // @phpstan-ignore-next-line
        return $this->belongsTo(config('auth.providers.users.model', User::class), 'user_id');
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    protected static function newFactory(): GeolocationFactory
    {
        return GeolocationFactory::new();
    }

    protected function casts(): array
    {
        return [
            'extreme_ip_lookup' => 'json',
            'ip_api' => 'json',
        ];
    }
}
