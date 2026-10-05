<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Models\Currency;
use Shopper\Core\Pricing\PricingContext;

trait ResolvesPricingContext
{
    protected function requestPricingContext(): PricingContext
    {
        $request = request();
        $request->attributes->set('shopper_calculated_prices', true);

        /** @var Currency|null $currency */
        $currency = $request->attributes->get('shopper_price_currency');

        /** @var Model|null $zone */
        $zone = $request->attributes->get('shopper_zone');

        /** @var Model|null $channel */
        $channel = $request->attributes->get('shopper_channel');

        $customerId = $request->user('sanctum')?->getAuthIdentifier();

        return new PricingContext(
            currencyCode: $currency?->code,
            customerId: $customerId === null ? null : (int) $customerId,
            channelId: $channel?->getKey(),
            zoneId: $zone?->getKey(),
        );
    }
}
