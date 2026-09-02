<?php

declare(strict_types=1);

namespace Shopper\Shipping;

use Illuminate\Support\Collection;
use Illuminate\Support\Manager;
use InvalidArgumentException;
use Shopper\Shipping\Contracts\ShippingDriver;
use Shopper\Shipping\Drivers\FedExDriver;
use Shopper\Shipping\Drivers\ManualDriver;
use Shopper\Shipping\Drivers\UpsDriver;
use Shopper\Shipping\Drivers\UspsDriver;
use UnitEnum;

final class ShippingManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return 'manual';
    }

    /**
     * @param  UnitEnum|string|null  $driver
     */
    public function driver($driver = null): ShippingDriver
    {
        return parent::driver($driver);
    }

    /**
     * @return array<int, string>
     */
    public function availableDrivers(): array
    {
        return array_unique(['manual', 'ups', 'fedex', 'usps', ...array_keys($this->customCreators)]);
    }

    /**
     * @return Collection<string, ShippingDriver>
     */
    public function configuredDrivers(): Collection
    {
        return collect($this->availableDrivers())
            ->filter(function (string $name): bool {
                if ($name === 'manual') {
                    return true;
                }

                return config("shopper.shipping.drivers.{$name}.enabled", false);
            })
            ->mapWithKeys(fn (string $name): array => [$name => $this->driver($name)]);
    }

    public function isConfigured(string $name): bool
    {
        try {
            return $this->driver($name)->isConfigured();
        } catch (InvalidArgumentException) {
            return false;
        }
    }

    protected function createManualDriver(): ManualDriver
    {
        return new ManualDriver;
    }

    protected function createUpsDriver(): UpsDriver
    {
        $config = config('shopper.shipping.drivers.ups', []);

        return new UpsDriver(
            clientId: $config['credentials']['client_id'] ?? '',
            clientSecret: $config['credentials']['client_secret'] ?? '',
            userId: $config['credentials']['user_id'] ?? '',
            accountNumber: $config['credentials']['account_number'] ?? '',
            sandbox: $config['sandbox'] ?? false,
        );
    }

    protected function createFedexDriver(): FedExDriver
    {
        $config = config('shopper.shipping.drivers.fedex', []);

        return new FedExDriver(
            clientId: $config['credentials']['client_id'] ?? '',
            clientSecret: $config['credentials']['client_secret'] ?? '',
            accountNumber: $config['credentials']['account_number'] ?? '',
            sandbox: $config['sandbox'] ?? false,
        );
    }

    protected function createUspsDriver(): UspsDriver
    {
        $config = config('shopper.shipping.drivers.usps', []);

        return new UspsDriver(
            clientId: $config['credentials']['client_id'] ?? '',
            clientSecret: $config['credentials']['client_secret'] ?? '',
            sandbox: $config['sandbox'] ?? false,
        );
    }
}
