<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components;

use Exception;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Reflector;
use Livewire\Component;
use Livewire\Mechanisms\ComponentRegistry;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Shopper\Contracts\ModalContract;

class Modal extends Component
{
    public ?string $activeComponent = null;

    /** @var array<string, mixed> */
    public array $components = [];

    public function resetState(): void
    {
        $this->components = [];
        $this->activeComponent = null;
    }

    /**
     * @param  array<string, mixed>  $arguments
     * @param  array<string, mixed>  $modalAttributes
     *
     * @throws ReflectionException
     */
    public function openModal(string $component, array $arguments = [], array $modalAttributes = []): void
    {
        $requiredInterface = ModalContract::class;
        $componentClass = app(ComponentRegistry::class)->getClass($component);
        $reflect = new ReflectionClass($componentClass);

        if ($reflect->implementsInterface($requiredInterface) === false) {
            throw new Exception("[{$componentClass}] does not implement [{$requiredInterface}] interface.");
        }

        $id = md5($component.serialize($arguments));

        $arguments = collect($arguments)
            ->merge($this->resolveComponentProps($arguments, new $componentClass))
            ->all();

        $this->components[$id] = [
            'name' => $component,
            'arguments' => $arguments,
            'modalAttributes' => array_merge([
                'closeOnClickAway' => $componentClass::closeModalOnClickAway(),
                'closeOnEscape' => $componentClass::closeModalOnEscape(),
                'closeOnEscapeIsForceful' => $componentClass::closeModalOnEscapeIsForceful(),
                'dispatchCloseEvent' => $componentClass::dispatchCloseEvent(),
                'destroyOnClose' => $componentClass::destroyOnClose(),
                'maxWidth' => $componentClass::modalMaxWidth(),
                'maxWidthClass' => $componentClass::modalMaxWidthClass(),
            ], $modalAttributes),
        ];

        $this->activeComponent = $id;

        $this->dispatch('activeModalComponentChanged', id: $id);
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return Collection<string, mixed>
     */
    public function resolveComponentProps(array $attributes, Component $component): Collection
    {
        return $this->getPublicPropertyTypes($component)
            ->intersectByKeys($attributes)
            ->map(function ($className, $propName) use ($attributes) {
                $resolved = $this->resolveParameter($attributes, $propName, $className);

                return $resolved;
            });
    }

    /**
     * @return Collection<string, string|null>
     */
    public function getPublicPropertyTypes(Component $component): Collection
    {
        return collect($component->all())
            ->map(fn ($value, $name) => Reflector::getParameterClassName(new ReflectionProperty($component, $name))) // @phpstan-ignore-line
            ->filter();
    }

    public function destroyComponent(string $id): void
    {
        unset($this->components[$id]);
    }

    /**
     * @return array<string>
     */
    public function getListeners(): array
    {
        return [
            'openModal',
            'destroyComponent',
        ];
    }

    public function render(): View
    {
        return view('shopper::livewire.components.modal');
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    protected function resolveParameter(array $attributes, string $parameterName, ?string $parameterClassName): mixed
    {
        $parameterValue = $attributes[$parameterName];

        if ($parameterValue instanceof UrlRoutable) {
            return $parameterValue;
        }

        if (enum_exists($parameterClassName)) {
            $enum = $parameterClassName::tryFrom($parameterValue); // @phpstan-ignore-line

            if ($enum !== null) {
                return $enum;
            }
        }

        $instance = app()->make($parameterClassName);

        if (! $model = $instance->resolveRouteBinding($parameterValue)) {
            throw (new ModelNotFoundException)->setModel(get_class($instance), [$parameterValue]);
        }

        return $model;
    }
}
