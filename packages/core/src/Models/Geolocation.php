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
 * @property int $user_id
 * @property int $order_id
 * @property array | null $ip_api
 * @property array | null $extreme_ip_lookup
 * @property-read \Illuminate\Foundation\Auth\User | User $user
 * @property-read Order $order
 */
class Geolocation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'extreme_ip_lookup' => 'json',
        'ip_api' => 'json',
    ];

    public function getTable(): string
    {
        return shopper_table('users_geolocation_history');
    }

    protected static function newFactory(): GeolocationFactory
    {
        return GeolocationFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', User::class), 'user_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
