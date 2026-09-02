<?php

declare(strict_types=1);

namespace Shopper\Core\Import;

use Illuminate\Support\Collection;
use Illuminate\Support\Manager;
use Shopper\Core\Import\Contracts\ImportSource;
use Shopper\Core\Import\Sources\CsvSource;
use Throwable;
use UnitEnum;

final class ImportManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return 'csv';
    }

    /**
     * @param  UnitEnum|string|null  $driver
     */
    public function driver($driver = null): ImportSource
    {
        return parent::driver($driver);
    }

    public function source(?string $name = null): ImportSource
    {
        return $this->driver($name);
    }

    /**
     * @return array<int, string>
     */
    public function availableSources(): array
    {
        return array_unique(['csv', ...array_keys($this->customCreators)]);
    }

    /**
     * @return Collection<string, ImportSource>
     */
    public function configuredSources(): Collection
    {
        return collect($this->availableSources())
            ->filter(fn (string $name): bool => $this->isConfigured($name))
            ->mapWithKeys(fn (string $name): array => [$name => $this->source($name)]);
    }

    public function isConfigured(string $name): bool
    {
        try {
            return $this->source($name)->isConfigured();
        } catch (Throwable) {
            return false;
        }
    }

    protected function createCsvDriver(): CsvSource
    {
        return new CsvSource;
    }
}
