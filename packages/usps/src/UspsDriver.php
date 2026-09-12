<?php

declare(strict_types=1);

namespace Shopper\Usps;

use Closure;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Shopper\Shipping\Concerns\InteractsWithCarrierApi;
use Shopper\Shipping\DataTransferObjects\Address;
use Shopper\Shipping\DataTransferObjects\Package;
use Shopper\Shipping\DataTransferObjects\ShippingRate;
use Shopper\Shipping\Drivers\Driver;
use Shopper\Shipping\Exceptions\ShippingException;
use Throwable;

final class UspsDriver extends Driver
{
    use InteractsWithCarrierApi;

    private const CURRENCY = 'USD';

    public function __construct(
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly bool $sandbox = false,
    ) {}

    public function code(): string
    {
        return 'usps';
    }

    public function name(): string
    {
        return 'USPS';
    }

    public function logo(): ?string
    {
        return function_exists('shopper_panel_assets')
            ? shopper_panel_assets('/images/carriers/usps.svg')
            : null;
    }

    public function supportsLabels(): bool
    {
        return false;
    }

    public function supportsTracking(): bool
    {
        return false;
    }

    public function isConfigured(): bool
    {
        return filled($this->clientId)
            && filled($this->clientSecret);
    }

    public function calculateRates(Address $from, Address $to, array $packages): Collection
    {
        if (! $this->isConfigured()) {
            throw ShippingException::notConfigured('usps');
        }

        $packages = $this->normalizePackages($packages, 'imperial');

        if ($packages === []) {
            throw ShippingException::invalidResponse('usps');
        }

        $quotes = [];

        foreach ($packages as $package) {
            foreach ($this->quotePackage($from, $to, $package) as $code => $quote) {
                $quotes[$code]['name'] ??= $quote['name'];
                $quotes[$code]['amount'] = ($quotes[$code]['amount'] ?? 0) + $quote['amount'];
                $quotes[$code]['packages'] = ($quotes[$code]['packages'] ?? 0) + 1;
            }
        }

        $rates = collect($quotes)
            ->filter(fn (array $quote): bool => $quote['packages'] === count($packages))
            ->map(fn (array $quote, string $code): ShippingRate => new ShippingRate(
                serviceCode: $code,
                serviceName: $quote['name'],
                amount: $quote['amount'],
                currency: self::CURRENCY,
                carrierCode: 'usps',
            ))
            ->values();

        if ($rates->isEmpty()) {
            throw ShippingException::invalidResponse('usps');
        }

        return $rates;
    }

    /**
     * @return array<string, array{name: string, amount: int}>
     */
    private function quotePackage(Address $from, Address $to, Package $package): array
    {
        $response = $this->send(
            fn (PendingRequest $request) => $request->post('/shipments/v3/options/search', $this->searchPayload($from, $to, $package))
        );

        if ($response->failed()) {
            throw ShippingException::apiError('usps', $this->carrierMessage($response));
        }

        $options = $response->json('pricingOptions');

        if (! is_array($options)) {
            throw ShippingException::invalidResponse('usps');
        }

        $quotes = [];

        foreach ($this->flattenRates($options) as $rate) {
            $code = $this->serviceCode($rate);
            $name = $this->carrierText($rate, 'productName');
            $price = data_get($rate, 'price');

            if ($code === null || $name === null || ! is_numeric($price)) {
                $this->dropRate($rate, 'service');

                continue;
            }

            $amount = (int) round((float) $price * 100);

            if ($amount <= 0) {
                $this->dropRate($rate, 'amount');

                continue;
            }

            $quotes[$code] = ['name' => $name, 'amount' => $amount];
        }

        return $quotes;
    }

    /**
     * @return array<string, mixed>
     */
    private function searchPayload(Address $from, Address $to, Package $package): array
    {
        $domestic = $from->country === 'US' && $to->country === 'US';

        $payload = [
            'pricingOptions' => [['priceType' => 'RETAIL']],
            'originZIPCode' => $from->postalCode,
            'destinationEntryFacilityType' => 'NONE',
            'packageDescription' => [
                'weight' => round($package->weight, 2),
                'weightUnit' => 'POUND',
                'length' => round($package->length, 2),
                'width' => round($package->width, 2),
                'height' => round($package->height, 2),
                'mailClass' => $domestic ? 'ALL_OUTBOUND' : 'ALL',
            ],
        ];

        if ($domestic) {
            $payload['destinationZIPCode'] = $to->postalCode;

            return $payload;
        }

        $payload['destinationCountryCode'] = $to->country;
        $payload['foreignPostalCode'] = $to->postalCode;

        return $payload;
    }

    /**
     * @param  array<int|string, mixed>  $options
     * @return array<int, array<string, mixed>>
     */
    private function flattenRates(array $options): array
    {
        return collect($options)
            ->pluck('shippingOptions')
            ->flatten(1)
            ->pluck('rateOptions')
            ->flatten(1)
            ->pluck('rates')
            ->flatten(1)
            ->filter(fn (mixed $rate): bool => is_array($rate))
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $rate
     */
    private function serviceCode(array $rate): ?string
    {
        $mailClass = $this->carrierText($rate, 'mailClass');
        $indicator = $this->carrierText($rate, 'rateIndicator');

        if ($mailClass === null) {
            return null;
        }

        return $indicator === null ? $mailClass : $mailClass.'-'.$indicator;
    }

    /**
     * @param  array<string, mixed>  $rate
     */
    private function dropRate(array $rate, string $reason): void
    {
        Log::warning('Discarded an unusable USPS rate.', [
            'driver' => 'usps',
            'mail_class' => $this->carrierText($rate, 'mailClass'),
            'reason' => $reason,
        ]);
    }

    private function api(): PendingRequest
    {
        return Http::baseUrl($this->sandbox ? 'https://apis-tem.usps.com' : 'https://apis.usps.com')
            ->acceptJson()
            ->timeout(15)
            ->retry(3, 200, fn (Throwable $e): bool => $e instanceof ConnectionException, throw: false);
    }

    /**
     * @param  Closure(PendingRequest): Response  $call
     */
    private function send(Closure $call): Response
    {
        try {
            return $call($this->api()->withToken($this->accessToken()));
        } catch (ConnectionException $e) {
            throw ShippingException::apiError('usps', $e->getMessage());
        }
    }

    private function accessToken(): string
    {
        $key = 'usps:'.sha1($this->clientId.'|'.($this->sandbox ? 'test' : 'live'));

        return $this->cachedToken($key, fn (): array => $this->readToken(
            $this->api()->asForm()->post('/oauth2/v3/token', [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ])
        ));
    }
}
