<?php

declare(strict_types=1);

use Shopper\FedEx\FedExDriver;
use Shopper\Shipping\Facades\Shipping;
use Shopper\Ups\UpsDriver;
use Shopper\Usps\UspsDriver;

uses(Tests\Shipping\TestCase::class);

it('registers each carrier package against the shipping manager', function (string $code, string $class): void {
    expect(Shipping::availableDrivers())->toContain($code)
        ->and(Shipping::driver($code))->toBeInstanceOf($class);
})->with([
    ['ups', UpsDriver::class],
    ['fedex', FedExDriver::class],
    ['usps', UspsDriver::class],
]);

it('reads the carrier credentials from the shipping driver config', function (string $code, array $credentials): void {
    expect(Shipping::isConfigured($code))->toBeFalse();

    config()->set("shopper.shipping.drivers.{$code}.credentials", $credentials);

    Shipping::forgetDrivers();

    expect(Shipping::isConfigured($code))->toBeTrue();
})->with([
    ['ups', ['client_id' => 'id', 'client_secret' => 'secret', 'user_id' => 'user', 'account_number' => '123456']],
    ['fedex', ['client_id' => 'id', 'client_secret' => 'secret', 'account_number' => '123456']],
    ['usps', ['client_id' => 'id', 'client_secret' => 'secret']],
]);

it('leaves a carrier out of the configured drivers until it is enabled', function (): void {
    config()->set('shopper.shipping.drivers.ups.enabled', false);

    expect(Shipping::configuredDrivers()->keys()->all())->toBe(['manual']);

    config()->set('shopper.shipping.drivers.ups.enabled', true);

    expect(Shipping::configuredDrivers()->keys()->all())->toBe(['manual', 'ups']);
});
