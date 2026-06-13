<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Cart;

use Illuminate\Support\Arr;
use Shopper\Api\Concerns\RespondsWithCart;
use Shopper\Api\Http\Requests\Cart\StoreCartAddressesRequest;
use Shopper\Cart\CartManager;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Models\Country;
use TiMacDonald\JsonApi\JsonApiResource;

final class CartAddressController
{
    use RespondsWithCart;

    public function __construct(
        private readonly CartManager $cartManager,
    ) {}

    /**
     * Set the checkout addresses of a cart.
     *
     * Send `shipping_address`, `billing_address` or both; each one replaces
     * the address of its type. The shipping address is what unlocks live
     * carrier rates on the shipping options endpoint.
     */
    public function store(StoreCartAddressesRequest $request, string $cartId): JsonApiResource
    {
        $cart = $this->findCart($request, $cartId);

        $addresses = [
            AddressType::Shipping->value => $request->validated('shipping_address'),
            AddressType::Billing->value => $request->validated('billing_address'),
        ];

        $countryIds = Country::query()
            ->whereIn('cca2', collect($addresses)->filter()->pluck('country_code')->unique())
            ->pluck('id', 'cca2');

        $this->mutateCart(function () use ($cart, $addresses, $countryIds): void {
            foreach ($addresses as $type => $address) {
                if (! $address) {
                    continue;
                }

                $this->cartManager->addAddress(
                    cart: $cart,
                    type: AddressType::from($type),
                    data: [
                        ...Arr::except($address, ['country_code']),
                        'country_id' => $countryIds[$address['country_code']],
                    ],
                );
            }
        });

        $cart->unsetRelation('addresses');

        return $this->cartResource($cart);
    }
}
