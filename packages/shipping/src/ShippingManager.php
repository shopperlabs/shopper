<?php

declare(strict_types=1);

namespace Shopper\Shipping;

use Closure;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Shopper\Shipping\Contracts\ShippingDriver;
use Shopper\Shipping\Drivers\FedExDriver;
use Shopper\Shipping\Drivers\ManualDriver;
use Shopper\Shipping\Drivers\UpsDriver;
use Shopper\Shipping\Drivers\UspsDriver;

final class ShippingManager
{
    /** @var array<string, ShippingDriver> */
    private array $drivers = [];

    /** @var array<string, Closure> */
    private array $customCreators = [];

    public function driver(?string $name = null): ShippingDriver
    {
        $name ??= 'manual';

        return $this->drivers[$name] ??= $this->resolve($name);
    }

    /**
     * Register a custom driver creator.
     */
    public function extend(string $name, Closure $callback): self
    {
        $this->customCreators[$name] = $callback;

        return $this;
    }

    /**
     * Get all available driver codes.
     *
     * @return array<int, string>
     */
    public function availableDrivers(): array
    {
        $builtIn = ['manual', 'ups', 'fedex', 'usps'];
        $custom = array_keys($this->customCreators);

        return array_unique([...$builtIn, ...$custom]);
    }

    /**
     * Get all configured and enabled drivers.
     *
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

    /**
     * Check if a driver is configured and ready to use.
     */
    public function isConfigured(string $name): bool
    {
        try {
            return $this->driver($name)->isConfigured();
        } catch (InvalidArgumentException) {
            return false;
        }
    }

    private function resolve(string $name): ShippingDriver
    {
        if (isset($this->customCreators[$name])) {
            return call_user_func($this->customCreators[$name], $name);
        }

        return match ($name) {
            'manual' => new ManualDriver,
            'ups' => $this->createUpsDriver(),
            'fedex' => $this->createFedExDriver(),
            'usps' => $this->createUspsDriver(),
            default => throw new InvalidArgumentException("Driver [{$name}] is not supported."),
        };
    }

    private function createUpsDriver(): UpsDriver
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

    private function createFedExDriver(): FedExDriver
    {
        $config = config('shopper.shipping.drivers.fedex', []);

        return new FedExDriver(
            clientId: $config['credentials']['client_id'] ?? '',
            clientSecret: $config['credentials']['client_secret'] ?? '',
            accountNumber: $config['credentials']['account_number'] ?? '',
            sandbox: $config['sandbox'] ?? false,
        );
    }

    private function createUspsDriver(): UspsDriver
    {
        $config = config('shopper.shipping.drivers.usps', []);

        return new UspsDriver(
            clientId: $config['credentials']['client_id'] ?? '',
            clientSecret: $config['credentials']['client_secret'] ?? '',
            sandbox: $config['sandbox'] ?? false,
        );
    }
}
