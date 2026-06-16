<?php

declare(strict_types=1);

namespace Shopper\Cart\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Cart\Database\Factories\CartAddressFactory;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Traits\HasPublicId;

/**
 * @property-read int $id
 * @property-read ?string $public_id
 * @property-read int $cart_id
 * @property-read AddressType $type
 * @property-read ?int $country_id
 * @property-read ?string $first_name
 * @property-read string $last_name
 * @property-read ?string $company
 * @property-read string $address_1
 * @property-read ?string $address_2
 * @property-read string $city
 * @property-read ?string $state
 * @property-read string $postal_code
 * @property-read ?string $phone
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read Cart $cart
 * @property-read ?Country $country
 * @property-read string $full_name
 */
class CartAddress extends Model
{
    /** @use HasFactory<CartAddressFactory> */
    use HasFactory;

    use HasPublicId;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('cart_addresses');
    }

    /**
     * @return BelongsTo<Cart, $this>
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(config('shopper.cart.models.cart', Cart::class));
    }

    /**
     * @return BelongsTo<Country, $this>
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    protected static function newFactory(): CartAddressFactory
    {
        return CartAddressFactory::new();
    }

    /**
     * @return Attribute<string, never>
     */
    protected function fullName(): Attribute
    {
        return Attribute::get(
            fn (): string => mb_trim("{$this->first_name} {$this->last_name}"),
        );
    }

    protected function casts(): array
    {
        return [
            'type' => AddressType::class,
        ];
    }
}
