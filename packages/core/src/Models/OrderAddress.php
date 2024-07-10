<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @protected string $last_name
 * @protected string $first_name
 * @protected string $street_address
 * @protected string | null $street_address_plus
 * @protected string $postal_code
 * @protected string $city
 * @protected string | null $company
 * @protected string | null $phone
 * @protected string | null $country_name
 */
class OrderAddress extends Model
{
    use HasFactory;

    public $fillable = [
        'last_name',
        'first_name',
        'company',
        'street_address',
        'street_address_plus',
        'postal_code',
        'city',
        'phone',
        'country_name',
        'customer_id',
    ];

    public function getTable(): string
    {
        return shopper_table('order_addresses');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', User::class), 'customer_id');
    }
}
