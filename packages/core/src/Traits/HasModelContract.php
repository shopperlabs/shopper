<?php

declare(strict_types=1);

namespace Shopper\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Shopper\Core\Exceptions\InvalidModelConfigurationException;

/**
 * @mixin Model
 */
trait HasModelContract
{
    protected static bool $dispatchesParentEvents = true;

    /**
     * Handle dynamic static method calls into the model.
     *
     * @phpstan-return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        if (! static::isShopperInstance()) {
            return (new (static::configuredClass()))->$method(...$parameters);
        }

        return (new static)->$method(...$parameters); // @phpstan-ignore new.static
    }

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
     * Check if the current class is the Shopper base class.
     */
    public static function isShopperInstance(): bool
    {
        return static::class === static::configuredClass();
    }

    /**
     * @return Builder<static>
     *
     * @deprecated Use standard Eloquent methods instead (e.g., Model::query())
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

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @return Builder<static>
     */
    public function newModelQuery(): Builder
    {
        $concreteClass = static::configuredClass();
        $shopperBaseClass = static::getShopperBaseClass();
        $parentClass = get_parent_class($concreteClass);

        // If the configured class directly extends a Shopper base model
        // OR if we're already an instance of the configured class, use parent behavior
        if ($parentClass === $shopperBaseClass || $this instanceof $concreteClass) {
            /** @var Builder<static> */
            return parent::newModelQuery();
        }

        /** @var Builder<static> */
        return $this->newEloquentBuilder(
            $this->newBaseQueryBuilder()
        )->setModel(
            static::withoutEvents(
                fn () => $this->replicateInto($concreteClass)
            )
        );
    }

    /**
     * Replicate the model into a new instance of a different class.
     *
     * @param  class-string<static>  $newClass
     * @return static
     */
    public function replicateInto(string $newClass): Model
    {
        $defaults = array_values(array_filter([
            $this->getKeyName(),
            $this->getCreatedAtColumn(),
            $this->getUpdatedAtColumn(),
            ...$this->uniqueIds(),
            'laravel_through_key',
        ]));

        $attributes = Arr::except(
            $this->getAttributes(),
            $defaults
        );

        /** @var static $instance */
        $instance = new $newClass;
        $instance->setRawAttributes($attributes);
        $instance->setRelations($this->relations);

        return $instance;
    }

    public function resolveRouteBinding($value, $field = null): ?static
    {
        $modelClass = static::configuredClass();

        return $modelClass::query()
            ->where($field ?? $this->getRouteKeyName(), $value)
            ->first();
    }

    public function resolveSoftDeletableRouteBinding($value, $field = null): ?static
    {
        $modelClass = static::configuredClass();

        return $modelClass::query()
            ->where($field ?? $this->getRouteKeyName(), $value)
            ->withTrashed() // @phpstan-ignore-line
            ->first();
    }

    public function getMorphClass(): string
    {
        $morphMap = Relation::morphMap();

        $configuredClass = static::configuredClass();
        $alias = array_search($configuredClass, $morphMap, true);

        if ($alias !== false) {
            return $alias;
        }

        $baseClass = static::getShopperBaseClass();

        if ($baseClass !== null && $baseClass !== static::class) {
            $alias = array_search($baseClass, $morphMap, true);

            if ($alias !== false) {
                return $alias;
            }

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

        $readOnlyEvents = ['retrieved', 'booting', 'booted'];

        if (in_array($event, $readOnlyEvents, true)) {
            return $result;
        }

        $method = $halt ? 'until' : 'dispatch';

        return static::$dispatcher->{$method}(
            "eloquent.{$event}: {$parentClass}",
            $this
        );
    }
}
