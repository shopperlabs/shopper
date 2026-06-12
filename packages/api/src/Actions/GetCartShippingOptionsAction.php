<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Illuminate\Support\Collection;
use Shopper\Api\Support\CartPackagesBuilder;
use Shopper\Api\Support\ShippingAddressFactory;
use Shopper\Api\Support\ShippingOption;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartLine;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\Product;
use Shopper\Shipping\DataTransferObjects\ShippingRate;
use Shopper\Shipping\Services\CarrierRateService;
use Symfony\Component\HttpFoundation\Response;

final class GetCartShippingOptionsAction
{
    public function __construct(
        private readonly CarrierRateService $rateService,
        private readonly ShippingAddressFactory $addressFactory,
        private readonly CartPackagesBuilder $packagesBuilder,
    ) {}

    /**
     * Collect the delivery choices for a cart. Manual zone rates only need
     * the cart zone, so they show up before the customer typed an address;
     * live carrier rates join once a shipping address exists. Options priced
     * in another currency than the cart and carriers that failed to quote
     * are dropped and surfaced as warnings instead of failing the request.
     *
     * @return array{options: Collection<int, ShippingOption>, warnings: array<int, string>}
     */
    public function execute(Cart $cart): array
    {
        $zone = $cart->zone;

        if (! $zone) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, __('The cart has no shipping zone, so no shipping option applies to it.'));
        }

        $lines = $this->shippableLines($cart);

        if ($lines->isEmpty()) {
            return ['options' => collect(), 'warnings' => []];
        }

        $warnings = [];
        $origin = null;

        $shippingAddress = $cart->shippingAddress();
        $destination = $shippingAddress ? $this->addressFactory->fromCartAddress($shippingAddress) : null;

        if ($destination) {
            $origin = $this->addressFactory->origin();

            if (! $origin) {
                $warnings[] = __('Live carrier rates are unavailable: no inventory location can act as the shipment origin.');
            }
        }

        $result = $this->rateService->getZoneRates(
            zone: $zone,
            from: $origin,
            to: $destination,
            packages: $this->packagesBuilder->build($lines),
        );

        foreach ($result->failedCarriers as $carrierName) {
            $warnings[] = __('Rates from ":carrier" are temporarily unavailable.', ['carrier' => $carrierName]);
        }

        $carrierNames = $zone->carriers
            ->mapWithKeys(fn (Carrier $carrier): array => [($carrier->slug ?? $carrier->name) => $carrier->name]);

        $droppedCurrencies = [];

        $options = $result->rates
            ->filter(function (ShippingRate $rate) use ($cart, &$droppedCurrencies): bool {
                if (strcasecmp($rate->currency, $cart->currency_code) === 0) {
                    return true;
                }

                $droppedCurrencies[] = $rate->currency;

                return false;
            })
            ->map(fn (ShippingRate $rate): ShippingOption => new ShippingOption(
                rate: $rate,
                carrierName: $carrierNames->get($rate->carrierCode),
            ))
            ->values();

        foreach (array_unique($droppedCurrencies) as $currency) {
            $warnings[] = __('Options priced in :currency were removed: the cart is priced in :cart_currency.', [
                'currency' => $currency,
                'cart_currency' => $cart->currency_code,
            ]);
        }

        return ['options' => $options, 'warnings' => $warnings];
    }

    /**
     * Virtual and external products never ship; a cart holding only those
     * needs no delivery choice at all.
     *
     * @return Collection<int, CartLine>
     */
    private function shippableLines(Cart $cart): Collection
    {
        return $cart->lines
            ->filter(function (CartLine $line): bool {
                $purchasable = $line->purchasable;

                if ($purchasable instanceof Product) {
                    return ! in_array($purchasable->type, [ProductType::Virtual, ProductType::External], true);
                }

                return $purchasable !== null;
            })
            ->values();
    }
}
