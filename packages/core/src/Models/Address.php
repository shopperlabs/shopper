<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Core\Database\Factories\AddressFactory;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Observers\AddressObserver;

/**
 * @property-read int $id
 * @property string $last_name
 * @property string | null $first_name
 * @property string $full_name
 * @property string | null $company_name
 * @property string $street_address
 * @property string | null $street_address_plus
 * @property string $postal_code
 * @property string $city
 * @property AddressType $type
 * @property string | null $phone_number
 * @property bool $shipping_default
 * @property bool $billing_default
 * @property int $user_id
 * @property int $country_id
 */
#[ObservedBy(AddressObserver::class)]
class Address extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'billing_default' => 'boolean',
        'shipping_default' => 'boolean',
        'type' => AddressType::class,
    ];

    public function getTable(): string
    {
        return shopper_table('user_addresses');
    }

    protected static function newFactory(): AddressFactory
    {
        return AddressFactory::new();
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name
                ? $this->first_name . ' ' . $this->last_name
                : $this->last_name
        );
    }

    public function isShippingDefault(): bool
    {
        return $this->shipping_default === true;
    }

    public function isBillingDefault(): bool
    {
        return $this->billing_default === true;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', User::class), 'user_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
