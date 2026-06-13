<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Shopper\Api\Support\PaymentSession;

/**
 * Serializes a driver-initiated payment session, not an Eloquent model. The
 * id is the driver reference (a Stripe payment intent id, ...) the client
 * confirms against; the attributes carry whatever the driver needs on the
 * storefront side (client secret, publishable key, redirect url).
 *
 * @property PaymentSession $resource
 */
final class PaymentSessionResource extends JsonApiResource
{
    /**
     * The driver `data` array is an internal grab bag: only keys a storefront
     * legitimately needs cross the wire. Everything else (provider object
     * ids, charge references, whatever a third-party driver stuffed in)
     * stays server-side.
     */
    private const PUBLIC_DATA = ['publishable_key', 'idempotency_key', 'stripe_status'];

    public function toId(Request $request): string
    {
        return $this->resource->id();
    }

    public function toType(Request $request): string
    {
        return 'payment-sessions';
    }

    public function toAttributes(Request $request): array
    {
        $result = $this->resource->result;

        return [
            'driver' => $this->resource->driver,
            'status' => $result->status,
            'amount' => $result->amount,
            'currency_code' => $this->resource->cart->currency_code,
            'client_secret' => $result->clientSecret,
            'redirect_url' => $result->redirectUrl,
            'data' => Arr::only($result->data, self::PUBLIC_DATA),
        ];
    }
}
