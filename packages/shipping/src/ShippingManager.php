<?php

declare(strict_types=1);

namespace Shopper\Shipping;

use Illuminate\Support\Collection;
use Illuminate\Support\Manager;
use InvalidArgumentException;
use Shopper\Shipping\Contracts\ShippingDriver;
use Shopper\Shipping\Drivers\ManualDriver;
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
        return array_unique(['manual', ...array_keys($this->customCreators)]);
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

                return config("shopper.{$name}.enabled", false);
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
}
