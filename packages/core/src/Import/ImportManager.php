<?php

declare(strict_types=1);

namespace Shopper\Core\Import;

use Closure;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Shopper\Core\Import\Contracts\ImportSource;
use Shopper\Core\Import\Sources\CsvSource;
use Throwable;

final class ImportManager
{
    /** @var array<string, ImportSource> */
    private array $sources = [];

    /** @var array<string, Closure> */
    private array $customCreators = [];

    public function source(?string $name = null): ImportSource
    {
        $name ??= 'csv';

        return $this->sources[$name] ??= $this->resolve($name);
    }

    public function extend(string $name, Closure $callback): self
    {
        $this->customCreators[$name] = $callback;

        return $this;
    }

    /**
     * @return array<int, string>
     */
    public function availableSources(): array
    {
        $builtIn = ['csv'];
        $custom = array_keys($this->customCreators);

        return array_unique([...$builtIn, ...$custom]);
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

    private function resolve(string $name): ImportSource
    {
        if (isset($this->customCreators[$name])) {
            return call_user_func($this->customCreators[$name], $name);
        }

        return match ($name) {
            'csv' => new CsvSource,
            default => throw new InvalidArgumentException("Import source [{$name}] is not supported."),
        };
    }
}
