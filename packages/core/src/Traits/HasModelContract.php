<?php

declare(strict_types=1);

namespace Shopper\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Exceptions\InvalidModelConfigurationException;

/**
 * @mixin Model
 */
trait HasModelContract
{
    protected static bool $dispatchesParentEvents = true;

    abstract public static function configKey(): string;

    public static function bootHasModelContract(): void
    {
        static::validateModelConfiguration();
    }

    /**
     * @return class-string<static>
     */
    public static function configuredClass(): string
    {
        /** @var class-string<static> */
        return config('shopper.models.'.static::configKey(), static::class);
    }

    /**
     * @return Builder<static>
     */
    public static function resolvedQuery(): Builder
    {
        return static::configuredClass()::query();
    }

    /**
     * @param  class-string  $observer
     */
    public static function observeUsingConfiguredClass(string $observer): void
    {
        static::configuredClass()::observe($observer);
    }

    public function resolveRouteBinding($value, $field = null): ?static
    {
        $modelClass = static::configuredClass();

        return (new $modelClass)::resolvedQuery()
            ->where($field ?? $this->getRouteKeyName(), $value)
            ->first();
    }

    public function resolveSoftDeletableRouteBinding($value, $field = null): ?static
    {
        $modelClass = static::configuredClass();

        return (new $modelClass)::resolvedQuery()
            ->where($field ?? $this->getRouteKeyName(), $value)
            ->withTrashed() // @phpstan-ignore-line
            ->first();
    }

    public function getMorphClass(): string
    {
        $baseClass = static::getShopperBaseClass();

        if ($baseClass !== null && $baseClass !== static::class) {
            return $baseClass;
        }

        return parent::getMorphClass();
    }

    protected static function validateModelConfiguration(): void
    {
        $configuredClass = static::configuredClass();
        $baseClass = static::class;

        if ($configuredClass === $baseClass) {
            return;
        }

        if (! is_subclass_of($configuredClass, $baseClass)) {
            throw new InvalidModelConfigurationException(
                "[{$configuredClass}] must extend [{$baseClass}]"
            );
        }
    }

    protected static function getShopperBaseClass(): ?string
    {
        $class = static::class;

        while ($parent = get_parent_class($class)) {
            if (str_starts_with($parent, 'Shopper\\Core\\Models\\')) {
                return $parent;
            }
            $class = $parent;
        }

        return null;
    }

    protected function fireModelEvent($event, $halt = true): mixed
    {
        $result = parent::fireModelEvent($event, $halt);

        if (! static::$dispatchesParentEvents) {
            return $result;
        }

        $parentClass = static::getShopperBaseClass();

        if ($parentClass === null || $parentClass === static::class) {
            return $result;
        }

        $method = $halt ? 'until' : 'dispatch';

        return static::$dispatcher->{$method}(
            "eloquent.{$event}: {$parentClass}",
            $this
        );
    }
}
