<?php

declare(strict_types=1);

namespace Shopper\Core\Channel;

use Closure;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Shopper\Core\Channel\Contracts\ChannelDriver;
use Shopper\Core\Channel\Drivers\WebDriver;
use Throwable;

final class ChannelManager
{
    /** @var array<string, ChannelDriver> */
    private array $drivers = [];

    /** @var array<string, Closure> */
    private array $customCreators = [];

    public function driver(?string $name = null): ChannelDriver
    {
        $name ??= 'web';

        return $this->drivers[$name] ??= $this->resolve($name);
    }

    public function extend(string $name, Closure $callback): self
    {
        $this->customCreators[$name] = $callback;

        return $this;
    }

    /**
     * @return array<int, string>
     */
    public function availableDrivers(): array
    {
        $builtIn = ['web'];
        $custom = array_keys($this->customCreators);

        return array_unique([...$builtIn, ...$custom]);
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

    private function resolve(string $name): ChannelDriver
    {
        if (isset($this->customCreators[$name])) {
            return call_user_func($this->customCreators[$name], $name);
        }

        return match ($name) {
            'web' => new WebDriver,
            default => throw new InvalidArgumentException("Channel driver [{$name}] is not supported."),
        };
    }
}
