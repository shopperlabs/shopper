<?php

declare(strict_types=1);

namespace Shopper\Core\Channel;

use Illuminate\Support\Collection;
use Illuminate\Support\Manager;
use Shopper\Core\Channel\Contracts\ChannelDriver;
use Shopper\Core\Channel\Drivers\WebDriver;
use Throwable;
use UnitEnum;

final class ChannelManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return 'web';
    }

    /**
     * @param  UnitEnum|string|null  $driver
     */
    public function driver($driver = null): ChannelDriver
    {
        return parent::driver($driver);
    }

    /**
     * @return array<int, string>
     */
    public function availableDrivers(): array
    {
        return array_unique(['web', ...array_keys($this->customCreators)]);
    }

    /**
     * @return Collection<string, ChannelDriver>
     */
    public function configuredDrivers(): Collection
    {
        return collect($this->availableDrivers())
            ->filter(fn (string $name): bool => $this->isConfigured($name))
            ->mapWithKeys(fn (string $name): array => [$name => $this->driver($name)]);
    }

    public function isConfigured(string $name): bool
    {
        try {
            return $this->driver($name)->isConfigured();
        } catch (Throwable) {
            return false;
        }
    }

    public function logoFor(?string $driver): ?string
    {
        $driver ??= 'web';

        return in_array($driver, $this->availableDrivers(), true)
            ? $this->driver($driver)->logo()
            : null;
    }

    protected function createWebDriver(): WebDriver
    {
        return new WebDriver;
    }
}
