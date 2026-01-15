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
use Shopper\Core\Models\Contracts\Address as AddressContract;
use Shopper\Core\Models\Contracts\ShopperUser;
use Shopper\Core\Observers\AddressObserver;
use Shopper\Core\Traits\HasModelContract;

/**
 * @property-read int $id
 * @property-read string $last_name
 * @property-read ?string $first_name
 * @property-read string $full_name
 * @property-read ?string $company_name
 * @property-read string $street_address
 * @property-read ?string $street_address_plus
 * @property-read string $postal_code
 * @property-read string $city
 * @property-read AddressType $type
 * @property-read ?string $phone_number
 * @property-read bool $shipping_default
 * @property-read bool $billing_default
 * @property-read int $user_id
 * @property-read int $country_id
 * @property-read Country $country
 * @property-read Model&ShopperUser $user
 */
#[ObservedBy(AddressObserver::class)]
class Address extends Model implements AddressContract
{
    /** @use HasFactory<AddressFactory> */
    use HasFactory;

    use HasModelContract;

    protected $guarded = [];

    public static function configKey(): string
    {
        return 'address';
    }

    public function getTable(): string
    {
        return shopper_table('user_addresses');
    }

    public function isShippingDefault(): bool
    {
        return $this->shipping_default === true;
    }

    public function isBillingDefault(): bool
    {
        return $this->billing_default === true;
    }

    /**
     * @return BelongsTo<Model&ShopperUser, $this>
     */
    public function user(): BelongsTo
    {
        // @phpstan-ignore-next-line
        return $this->belongsTo(config('auth.providers.users.model'), 'user_id');
    }

    /**
     * @return BelongsTo<Country, $this>
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    protected static function newFactory(): AddressFactory
    {
        return AddressFactory::new();
    }

    protected function casts(): array
    {
        return [
            'billing_default' => 'boolean',
            'shipping_default' => 'boolean',
            'type' => AddressType::class,
        ];
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->first_name
                ? implode(' ', [$this->first_name, $this->last_name])
                : $this->last_name
        );
    }
}
