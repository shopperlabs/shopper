<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Shopper\FedEx\FedExDriver;
use Shopper\Shipping\DataTransferObjects\Address;
use Shopper\Shipping\DataTransferObjects\Package;
use Shopper\Shipping\DataTransferObjects\ShippingRate;
use Shopper\Shipping\Exceptions\ShippingException;
use Shopper\Ups\UpsDriver;
use Shopper\Usps\UspsDriver;

uses(Tests\Shipping\TestCase::class);

function rateDriver(bool $sandbox = false): UpsDriver
{
    return new UpsDriver('client', 'secret', 'user', '123456', sandbox: $sandbox);
}

function warehouse(): Address
{
    return new Address(
        firstName: 'Arthur',
        lastName: 'Monney',
        street: '1 Rue de la Paix',
        city: 'Paris',
        postalCode: '75002',
        state: 'IDF',
        country: 'FR',
        street2: 'Batiment B',
    );
}

function destination(): Address
{
    return new Address(
        firstName: 'Jane',
        lastName: 'Doe',
        street: '350 5th Ave',
        city: 'New York',
        postalCode: '10118',
        state: 'NY',
        country: 'US',
    );
}

/**
 * @return array<string, mixed>
 */
function upsRateResponse(): array
{
    return [
        'RateResponse' => [
            'Response' => ['ResponseStatus' => ['Code' => '1', 'Description' => 'Success']],
            'RatedShipment' => [
                [
                    'Service' => ['Code' => '03', 'Description' => 'UPS Ground'],
                    'TotalCharges' => ['CurrencyCode' => 'USD', 'MonetaryValue' => '12.34'],
                    'GuaranteedDelivery' => ['BusinessDaysInTransit' => '3'],
                ],
                [
                    'Service' => ['Code' => '01'],
                    'TotalCharges' => ['CurrencyCode' => 'USD', 'MonetaryValue' => '48.90'],
                ],
            ],
        ],
    ];
}

function fakeUpsRates(mixed $body, int $status = 200): void
{
    Http::fake([
        'onlinetools.ups.com/security/v1/oauth/token' => Http::response(['access_token' => 'ups-token', 'expires_in' => 14399]),
        'onlinetools.ups.com/api/rating/*' => Http::response($body, $status),
    ]);
}

/**
 * @return array<string, mixed>
 */
function sentRateRequest(): array
{
    $body = [];

    Http::assertSent(function ($request) use (&$body): bool {
        if (str_contains($request->url(), '/api/rating/')) {
            $body = $request->data();
        }

        return true;
    });

    return $body;
}

it('turns the UPS rate shop response into shipping rates', function (): void {
    fakeUpsRates(upsRateResponse());

    $rates = rateDriver()->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)]);

    expect($rates)->toHaveCount(2)
        ->and($rates->map(fn (ShippingRate $rate): array => [
            $rate->serviceCode,
            $rate->serviceName,
            $rate->amount,
            $rate->currency,
            $rate->carrierCode,
            $rate->estimatedDays,
        ])->all())->toBe([
            ['03', 'UPS Ground', 1234, 'USD', 'ups', '3'],
            ['01', 'UPS Next Day Air', 4890, 'USD', 'ups', null],
        ]);
});

it('sends the account number, both address lines and metric units to UPS', function (): void {
    fakeUpsRates(upsRateResponse());

    rateDriver()->calculateRates(warehouse(), destination(), [new Package(30.0, 20.0, 10.0, 2.5)]);

    $shipment = sentRateRequest()['RateRequest']['Shipment'];

    expect(sentRateRequest()['RateRequest']['PickupType'])->toBe(['Code' => '01'])
        ->and(sentRateRequest()['RateRequest']['CustomerClassification'])->toBe(['Code' => '01'])
        ->and($shipment['Shipper']['ShipperNumber'])->toBe('123456')
        ->and($shipment['Shipper']['Address']['AddressLine'])->toBe(['1 Rue de la Paix', 'Batiment B'])
        ->and($shipment['ShipFrom']['Address']['CountryCode'])->toBe('FR')
        ->and($shipment['ShipTo']['Address'])->toBe([
            'AddressLine' => ['350 5th Ave'],
            'City' => 'New York',
            'StateProvinceCode' => 'NY',
            'PostalCode' => '10118',
            'CountryCode' => 'US',
        ])
        ->and($shipment['Package'])->toBe([[
            'PackagingType' => ['Code' => '02'],
            'Dimensions' => [
                'UnitOfMeasurement' => ['Code' => 'CM'],
                'Length' => '30',
                'Width' => '20',
                'Height' => '10',
            ],
            'PackageWeight' => [
                'UnitOfMeasurement' => ['Code' => 'KGS'],
                'Weight' => '2.5',
            ],
        ]])
        ->and($shipment)->not->toHaveKey('Service');
});

it('converts an imperial package to metric before quoting UPS', function (): void {
    fakeUpsRates(upsRateResponse());

    rateDriver()->calculateRates(warehouse(), destination(), [new Package(10, 10, 10, 10, unit: 'imperial')]);

    $package = sentRateRequest()['RateRequest']['Shipment']['Package'][0];

    expect($package['Dimensions']['Length'])->toBe('25.4')
        ->and($package['PackageWeight']['Weight'])->toBe('4.54');
});

it('skips a rated shipment that carries no charges', function (): void {
    fakeUpsRates(['RateResponse' => ['RatedShipment' => [
        ['Service' => ['Code' => '03']],
        [
            'Service' => ['Code' => '11', 'Description' => 'UPS Standard'],
            'TotalCharges' => ['CurrencyCode' => 'EUR', 'MonetaryValue' => '9.99'],
        ],
    ]]]);

    $rates = rateDriver()->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)]);

    expect($rates)->toHaveCount(1)
        ->and($rates->first()->serviceCode)->toBe('11')
        ->and($rates->first()->amount)->toBe(999);
});

it('surfaces a refused UPS rate request as an api error', function (): void {
    fakeUpsRates(['response' => ['errors' => [['code' => '250002', 'message' => 'Invalid Authentication Information']]]], 401);

    expect(fn () => rateDriver()->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)]))
        ->toThrow(ShippingException::class, 'API error from [ups]: Invalid Authentication Information');
});

it('rejects a UPS rate response that carries no rated shipment', function (): void {
    fakeUpsRates(['RateResponse' => ['Response' => ['ResponseStatus' => ['Code' => '1']]]]);

    expect(fn () => rateDriver()->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)]))
        ->toThrow(ShippingException::class, 'Invalid response received from [ups] API.');
});

it('quotes against the UPS sandbox host when the driver runs in sandbox mode', function (): void {
    Http::fake([
        'wwwcie.ups.com/security/v1/oauth/token' => Http::response(['access_token' => 'ups-sandbox', 'expires_in' => 14399]),
        'wwwcie.ups.com/api/rating/*' => Http::response(upsRateResponse()),
    ]);

    rateDriver(sandbox: true)->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)]);

    Http::assertSent(fn ($request): bool => str_starts_with($request->url(), 'https://wwwcie.ups.com/'));
});

it('refuses to quote before the UPS credentials are configured', function (): void {
    Http::fake();

    expect(fn () => (new UpsDriver('', '', '', ''))->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)]))
        ->toThrow(ShippingException::class, 'The [ups] shipping driver is not configured. Please check your .env file.');

    Http::assertNothingSent();
});

it('never quotes a UPS charge that is not a number', function (): void {
    fakeUpsRates(['RateResponse' => ['RatedShipment' => [
        ['Service' => ['Code' => '03'], 'TotalCharges' => ['CurrencyCode' => 'USD', 'MonetaryValue' => 'USD 12.34']],
        ['Service' => ['Code' => '01'], 'TotalCharges' => ['CurrencyCode' => 'USD', 'MonetaryValue' => '12,34']],
        ['Service' => ['Code' => '02'], 'TotalCharges' => ['CurrencyCode' => 'USD', 'MonetaryValue' => '20.00']],
    ]]]);

    $rates = rateDriver()->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)]);

    expect($rates)->toHaveCount(1)
        ->and($rates->first()->serviceCode)->toBe('02')
        ->and($rates->first()->amount)->toBe(2000);
});

it('never quotes a free or negative UPS charge', function (): void {
    fakeUpsRates(['RateResponse' => ['RatedShipment' => [
        ['Service' => ['Code' => '03'], 'TotalCharges' => ['CurrencyCode' => 'USD', 'MonetaryValue' => '0.00']],
        ['Service' => ['Code' => '01'], 'TotalCharges' => ['CurrencyCode' => 'USD', 'MonetaryValue' => '-5.00']],
    ]]]);

    expect(fn () => rateDriver()->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)]))
        ->toThrow(ShippingException::class, 'Invalid response received from [ups] API.');
});

it('reads a zero decimal currency whatever its case', function (): void {
    fakeUpsRates(['RateResponse' => ['RatedShipment' => [
        ['Service' => ['Code' => '11'], 'TotalCharges' => ['CurrencyCode' => 'jpy', 'MonetaryValue' => '1200']],
    ]]]);

    $rate = rateDriver()->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)])->first();

    expect($rate->amount)->toBe(1200)
        ->and($rate->currency)->toBe('JPY');
});

it('accepts a single UPS rated shipment that is not wrapped in a list', function (): void {
    fakeUpsRates(['RateResponse' => ['RatedShipment' => [
        'Service' => ['Code' => '03', 'Description' => 'UPS Ground'],
        'TotalCharges' => ['CurrencyCode' => 'USD', 'MonetaryValue' => '12.34'],
    ]]]);

    $rates = rateDriver()->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)]);

    expect($rates)->toHaveCount(1)
        ->and($rates->first()->amount)->toBe(1234);
});

function fedexRateDriver(bool $sandbox = false): FedExDriver
{
    return new FedExDriver('client', 'secret', '123456', sandbox: $sandbox);
}

/**
 * @param  array<int, array<string, mixed>>  $details
 * @return array<string, mixed>
 */
function fedexRateResponse(array $details): array
{
    return ['output' => ['rateReplyDetails' => $details]];
}

/**
 * @return array<int, array<string, mixed>>
 */
function fedexRateDetails(): array
{
    return [
        [
            'serviceType' => 'FEDEX_GROUND',
            'serviceName' => 'FedEx Ground',
            'ratedShipmentDetails' => [
                ['rateType' => 'LIST', 'totalNetCharge' => 19.99, 'currency' => 'USD'],
                ['rateType' => 'ACCOUNT', 'totalNetCharge' => 12.34, 'currency' => 'USD'],
            ],
        ],
        [
            'serviceType' => 'INTERNATIONAL_PRIORITY',
            'ratedShipmentDetails' => [
                ['rateType' => 'ACCOUNT', 'totalNetCharge' => 48.9, 'shipmentRateDetail' => ['currency' => 'EUR']],
            ],
        ],
    ];
}

function fakeFedExRates(mixed $body, int $status = 200, string $host = 'apis.fedex.com'): void
{
    Http::fake([
        $host.'/oauth/token' => Http::response(['access_token' => 'fedex-token', 'expires_in' => 3600]),
        $host.'/rate/v1/rates/quotes' => Http::response($body, $status),
    ]);
}

/**
 * @return array<string, mixed>
 */
function sentFedExRateRequest(): array
{
    $body = [];

    Http::assertSent(function ($request) use (&$body): bool {
        if (str_contains($request->url(), '/rate/v1/rates/quotes')) {
            $body = $request->data();
        }

        return true;
    });

    return $body;
}

it('turns the FedEx rate quotes into shipping rates', function (): void {
    fakeFedExRates(fedexRateResponse(fedexRateDetails()));

    $rates = fedexRateDriver()->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)]);

    expect($rates)->toHaveCount(2)
        ->and($rates->map(fn (ShippingRate $rate): array => [
            $rate->serviceCode,
            $rate->serviceName,
            $rate->amount,
            $rate->currency,
            $rate->carrierCode,
        ])->all())->toBe([
            ['FEDEX_GROUND', 'FedEx Ground', 1234, 'USD', 'fedex'],
            ['INTERNATIONAL_PRIORITY', 'INTERNATIONAL_PRIORITY', 4890, 'EUR', 'fedex'],
        ]);
});

it('quotes the FedEx account rate rather than the first entry the carrier happens to return', function (): void {
    fakeFedExRates(fedexRateResponse([
        [
            'serviceType' => 'FEDEX_GROUND',
            'ratedShipmentDetails' => [
                ['rateType' => 'LIST', 'totalNetCharge' => 19.99, 'currency' => 'USD'],
                ['rateType' => 'ACCOUNT', 'totalNetCharge' => 12.34, 'currency' => 'USD'],
            ],
        ],
    ]));

    $rates = fedexRateDriver()->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)]);

    expect($rates->first()->amount)->toBe(1234);
});

it('falls back to the only FedEx rate on offer when no account rate is quoted', function (): void {
    fakeFedExRates(fedexRateResponse([
        [
            'serviceType' => 'FEDEX_GROUND',
            'ratedShipmentDetails' => [
                ['rateType' => 'LIST', 'totalNetCharge' => 19.99, 'currency' => 'USD'],
            ],
        ],
    ]));

    expect(fedexRateDriver()->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)])->first()->amount)
        ->toBe(1999);
});

it('rounds a FedEx charge to the nearest minor unit instead of truncating it', function (): void {
    fakeFedExRates(fedexRateResponse([
        [
            'serviceType' => 'FEDEX_GROUND',
            'ratedShipmentDetails' => [['rateType' => 'ACCOUNT', 'totalNetCharge' => 8.45, 'currency' => 'USD']],
        ],
    ]));

    expect(fedexRateDriver()->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)])->first()->amount)
        ->toBe(845);
});

it('never multiplies a FedEx charge quoted in a currency without minor units', function (): void {
    fakeFedExRates(fedexRateResponse([
        [
            'serviceType' => 'FEDEX_GROUND',
            'ratedShipmentDetails' => [['rateType' => 'ACCOUNT', 'totalNetCharge' => 2500, 'currency' => 'jpy']],
        ],
    ]));

    $rate = fedexRateDriver()->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)])->first();

    expect($rate->amount)->toBe(2500)
        ->and($rate->currency)->toBe('JPY');
});

it('discards an unusable FedEx quote without losing the rest of the reply', function (): void {
    fakeFedExRates(fedexRateResponse([
        [
            'serviceType' => 'FEDEX_GROUND',
            'ratedShipmentDetails' => [['rateType' => 'ACCOUNT', 'totalNetCharge' => 'USD 12.34', 'currency' => 'USD']],
        ],
        [
            'serviceType' => 'FEDEX_2_DAY',
            'ratedShipmentDetails' => [['rateType' => 'ACCOUNT', 'totalNetCharge' => 0, 'currency' => 'USD']],
        ],
        [
            'serviceType' => 'FEDEX_EXPRESS_SAVER',
            'ratedShipmentDetails' => [['rateType' => 'ACCOUNT', 'totalNetCharge' => 31.5, 'currency' => 'USD']],
        ],
    ]));

    $rates = fedexRateDriver()->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)]);

    expect($rates)->toHaveCount(1)
        ->and($rates->first()->serviceCode)->toBe('FEDEX_EXPRESS_SAVER')
        ->and($rates->first()->amount)->toBe(3150);
});

it('sends the FedEx account number and the packages in metric units', function (): void {
    fakeFedExRates(fedexRateResponse(fedexRateDetails()));

    fedexRateDriver()->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)]);

    $shipment = sentFedExRateRequest()['requestedShipment'];

    expect(sentFedExRateRequest()['accountNumber'])->toBe(['value' => '123456'])
        ->and($shipment['rateRequestType'])->toBe(['ACCOUNT', 'LIST'])
        ->and($shipment['shipper']['address']['streetLines'])->toBe(['1 Rue de la Paix', 'Batiment B'])
        ->and($shipment['recipient']['address']['postalCode'])->toBe('10118')
        ->and($shipment['requestedPackageLineItems'])->toBe([[
            'weight' => ['units' => 'KG', 'value' => 2.0],
            'dimensions' => ['length' => 30.0, 'width' => 20.0, 'height' => 10.0, 'units' => 'CM'],
        ]]);
});

it('rates against the FedEx sandbox host when the driver runs in sandbox mode', function (): void {
    fakeFedExRates(fedexRateResponse(fedexRateDetails()), host: 'apis-sandbox.fedex.com');

    fedexRateDriver(sandbox: true)->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)]);

    Http::assertSent(fn ($request): bool => str_contains($request->url(), 'apis-sandbox.fedex.com'));
});

it('rejects a FedEx rate reply that quotes nothing', function (): void {
    fakeFedExRates(['output' => []]);

    expect(fn () => fedexRateDriver()->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)]))
        ->toThrow(ShippingException::class);
});

it('surfaces a FedEx rate outage as a retryable api error', function (): void {
    fakeFedExRates(['errors' => [['code' => 'SYSTEM.UNAVAILABLE', 'message' => 'Service unavailable']]], 503);

    expect(fn () => fedexRateDriver()->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)]))
        ->toThrow(ShippingException::class, 'Service unavailable');
});

it('refuses to rate before the FedEx credentials are configured', function (): void {
    expect(fn () => (new FedExDriver('', '', ''))->calculateRates(warehouse(), destination(), [new Package(30, 20, 10, 2)]))
        ->toThrow(ShippingException::class);

    Http::assertNothingSent();
});

function uspsRateDriver(bool $sandbox = false): UspsDriver
{
    return new UspsDriver('client', 'secret', sandbox: $sandbox);
}

function uspsWarehouse(): Address
{
    return new Address(
        firstName: 'Arthur',
        lastName: 'Monney',
        street: '1 Main St',
        city: 'Brooklyn',
        postalCode: '11201',
        state: 'NY',
        country: 'US',
    );
}

/**
 * @param  array<int, array<string, mixed>>  $rates
 * @return array<string, mixed>
 */
function uspsRateResponse(array $rates): array
{
    return ['pricingOptions' => [['shippingOptions' => [['rateOptions' => [['rates' => $rates]]]]]]];
}

/**
 * @return array<int, array<string, mixed>>
 */
function uspsRates(): array
{
    return [
        [
            'mailClass' => 'USPS_GROUND_ADVANTAGE',
            'rateIndicator' => 'SP',
            'productName' => 'USPS Ground Advantage',
            'price' => 8.45,
        ],
        [
            'mailClass' => 'PRIORITY_MAIL',
            'rateIndicator' => 'DR',
            'productName' => 'Priority Mail',
            'price' => 14.2,
        ],
    ];
}

function fakeUspsRates(mixed $body, int $status = 200, string $host = 'apis.usps.com'): void
{
    Http::fake([
        $host.'/oauth2/v3/token' => Http::response(['access_token' => 'usps-token', 'expires_in' => 3600]),
        $host.'/shipments/v3/options/search' => Http::response($body, $status),
    ]);
}

/**
 * @return array<string, mixed>
 */
function sentUspsRateRequest(): array
{
    $body = [];

    Http::assertSent(function ($request) use (&$body): bool {
        if (str_contains($request->url(), '/shipments/v3/options/search')) {
            $body = $request->data();
        }

        return true;
    });

    return $body;
}

it('turns the USPS price options into shipping rates', function (): void {
    fakeUspsRates(uspsRateResponse(uspsRates()));

    $rates = uspsRateDriver()->calculateRates(uspsWarehouse(), destination(), [new Package(12, 8, 4, 3, 'imperial')]);

    expect($rates)->toHaveCount(2)
        ->and($rates->every(fn (ShippingRate $rate): bool => $rate->carrierCode === 'usps'))->toBeTrue()
        ->and($rates->first()->serviceCode)->toBe('USPS_GROUND_ADVANTAGE-SP')
        ->and($rates->first()->serviceName)->toBe('USPS Ground Advantage')
        ->and($rates->first()->currency)->toBe('USD')
        ->and($rates->last()->amount)->toBe(1420);
});

it('rounds a USPS price to the nearest minor unit instead of truncating it', function (): void {
    fakeUspsRates(uspsRateResponse(uspsRates()));

    $rate = uspsRateDriver()->calculateRates(uspsWarehouse(), destination(), [new Package(12, 8, 4, 3, 'imperial')])->first();

    expect($rate->amount)->toBe(845);
});

it('totals a USPS service across every package and drops one the carrier omits', function (): void {
    Http::fake([
        'apis.usps.com/oauth2/v3/token' => Http::response(['access_token' => 'usps-token', 'expires_in' => 3600]),
        'apis.usps.com/shipments/v3/options/search' => Http::sequence()
            ->push(uspsRateResponse(uspsRates()))
            ->push(uspsRateResponse([[
                'mailClass' => 'USPS_GROUND_ADVANTAGE',
                'rateIndicator' => 'SP',
                'productName' => 'USPS Ground Advantage',
                'price' => 6.1,
            ]])),
    ]);

    $rates = uspsRateDriver()->calculateRates(uspsWarehouse(), destination(), [
        new Package(12, 8, 4, 3, 'imperial'),
        new Package(6, 4, 2, 1, 'imperial'),
    ]);

    expect($rates)->toHaveCount(1)
        ->and($rates->first()->serviceCode)->toBe('USPS_GROUND_ADVANTAGE-SP')
        ->and($rates->first()->amount)->toBe(1455);
});

it('discards an unusable USPS price without losing the rest of the reply', function (): void {
    fakeUspsRates(uspsRateResponse([
        ['mailClass' => 'PARCEL_SELECT', 'rateIndicator' => 'SP', 'productName' => 'Parcel Select', 'price' => 'USD 9.10'],
        ['mailClass' => 'MEDIA_MAIL', 'rateIndicator' => 'SP', 'productName' => 'Media Mail', 'price' => 0],
        ['mailClass' => 'LIBRARY_MAIL', 'rateIndicator' => 'SP', 'price' => 4.5],
        ['mailClass' => 'PRIORITY_MAIL', 'rateIndicator' => 'DR', 'productName' => 'Priority Mail', 'price' => 14.2],
    ]));

    $rates = uspsRateDriver()->calculateRates(uspsWarehouse(), destination(), [new Package(12, 8, 4, 3, 'imperial')]);

    expect($rates)->toHaveCount(1)
        ->and($rates->first()->serviceCode)->toBe('PRIORITY_MAIL-DR')
        ->and($rates->first()->amount)->toBe(1420);
});

it('sends the USPS domestic payload in pounds and inches', function (): void {
    fakeUspsRates(uspsRateResponse(uspsRates()));

    uspsRateDriver()->calculateRates(uspsWarehouse(), destination(), [new Package(12, 8, 4, 3, 'imperial')]);

    $payload = sentUspsRateRequest();

    expect($payload['originZIPCode'])->toBe('11201')
        ->and($payload['destinationZIPCode'])->toBe('10118')
        ->and($payload)->not->toHaveKey('destinationCountryCode')
        ->and($payload['pricingOptions'])->toBe([['priceType' => 'RETAIL']])
        ->and($payload['packageDescription'])->toBe([
            'weight' => 3.0,
            'weightUnit' => 'POUND',
            'length' => 12.0,
            'width' => 8.0,
            'height' => 4.0,
            'mailClass' => 'ALL_OUTBOUND',
        ]);
});

it('switches the USPS payload to international for a destination outside the US', function (): void {
    fakeUspsRates(uspsRateResponse(uspsRates()));

    uspsRateDriver()->calculateRates(uspsWarehouse(), warehouse(), [new Package(12, 8, 4, 3, 'imperial')]);

    $payload = sentUspsRateRequest();

    expect($payload['destinationCountryCode'])->toBe('FR')
        ->and($payload['foreignPostalCode'])->toBe('75002')
        ->and($payload)->not->toHaveKey('destinationZIPCode')
        ->and($payload['packageDescription']['mailClass'])->toBe('ALL');
});

it('converts a metric package before quoting USPS', function (): void {
    fakeUspsRates(uspsRateResponse(uspsRates()));

    uspsRateDriver()->calculateRates(uspsWarehouse(), destination(), [new Package(30, 20, 10, 2)]);

    $package = sentUspsRateRequest()['packageDescription'];

    expect($package['weightUnit'])->toBe('POUND')
        ->and($package['weight'])->toBe(4.41)
        ->and($package['length'])->toBe(11.81);
});

it('rates against the USPS test host when the driver runs in sandbox mode', function (): void {
    fakeUspsRates(uspsRateResponse(uspsRates()), host: 'apis-tem.usps.com');

    uspsRateDriver(sandbox: true)->calculateRates(uspsWarehouse(), destination(), [new Package(12, 8, 4, 3, 'imperial')]);

    Http::assertSent(fn ($request): bool => str_contains($request->url(), 'apis-tem.usps.com'));
});

it('rejects a USPS reply that quotes nothing', function (): void {
    fakeUspsRates(['pricingOptions' => []]);

    expect(fn () => uspsRateDriver()->calculateRates(uspsWarehouse(), destination(), [new Package(12, 8, 4, 3, 'imperial')]))
        ->toThrow(ShippingException::class);
});

it('surfaces a USPS rate outage as a retryable api error', function (): void {
    fakeUspsRates(['error' => ['message' => 'No valid rates for these parameters']], 503);

    expect(fn () => uspsRateDriver()->calculateRates(uspsWarehouse(), destination(), [new Package(12, 8, 4, 3, 'imperial')]))
        ->toThrow(ShippingException::class, 'No valid rates for these parameters');
});

it('refuses to rate before the USPS credentials are configured', function (): void {
    expect(fn () => (new UspsDriver('', ''))->calculateRates(uspsWarehouse(), destination(), [new Package(12, 8, 4, 3, 'imperial')]))
        ->toThrow(ShippingException::class);

    Http::assertNothingSent();
});
