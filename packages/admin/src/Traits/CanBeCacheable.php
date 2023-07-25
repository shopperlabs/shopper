<?php

declare(strict_types=1);

namespace Shopper\Traits;

use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Support\Facades\Cache;

trait CanBeCacheable
{
    protected null|bool|Closure $cacheable = null;

    protected null|int|DateInterval|DateTimeInterface|Closure $cacheDuration = null;

    protected array $evaluationParametersToRemove = [];

    public function cacheable(bool|Closure $cacheable = true): static
    {
        $this->cacheable = $cacheable;

        return $this;
    }

    public function getCacheable(): bool
    {
        $cacheable = $this->cacheable ?? config('shopper.admin.icon-picker.cache', true);

        return $this->evaluate($cacheable);
    }

    public function cacheDuration(int|DateInterval|DateTimeInterface|Closure $cacheDuration): static
    {
        $this->cacheDuration = $cacheDuration;

        return $this;
    }

    public function getCacheDuration()
    {
        $duration = $this->cacheDuration ?? now()->add(config('shopper.admin.icon-picker.duration', '7 days'));

        return $this->evaluate($duration);
    }

    protected function tryCache(string $key, Closure $callback)
    {
        if (! $this->getCacheable()) {
            return $callback->call($this);
        }

        return Cache::remember($key, $this->getCacheDuration(), $callback);
    }

    public function evaluate($value, array $parameters = [], array $exceptParameters = [])
    {
        $this->evaluationParametersToRemove = $exceptParameters;

        if ($value instanceof Closure) {
            return app()->call(
                $value,
                array_merge(
                    isset($this->evaluationIdentifier) ? [$this->evaluationIdentifier => $this] : [],
                    $parameters,
                ),
            );
        }

        return $value;
    }
}
