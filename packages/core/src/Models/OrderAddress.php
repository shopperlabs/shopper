<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property string $last_name
 * @property string $first_name
 * @property string $full_name
 * @property string $street_address
 * @property string | null $street_address_plus
 * @property string $postal_code
 * @property string $city
 * @property string | null $company
 * @property string | null $phone
 * @property string | null $country_name
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

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name
                ? $this->first_name . ' ' . $this->last_name
                : $this->last_name
        );
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
