<?php

declare(strict_types=1);

namespace Shopper\Discounts;

use Closure;
use Filament\Schemas\Components\Component;

final class DiscountEligibilityFieldRegistry
{
    /** @var array<string, array{formKey: string, field: Closure(): Component, resolveIds: Closure(array<int>): array<int>}> */
    private array $modes = [];

    /**
     * @param  Closure(): Component  $field
     * @param  Closure(array<int>): array<int>  $resolveIds
     */
    public function register(string $eligibilityKey, string $formKey, Closure $field, Closure $resolveIds): void
    {
        $this->modes[$eligibilityKey] = [
            'formKey' => $formKey,
            'field' => $field,
            'resolveIds' => $resolveIds,
        ];
    }

    public function formKeyFor(string $eligibilityKey): ?string
    {
        return $this->modes[$eligibilityKey]['formKey'] ?? null;
    }

    /**
     * @param  array<int>  $rawIds
     * @return array<int>
     */
    public function resolveIds(string $eligibilityKey, array $rawIds): array
    {
        $resolver = $this->modes[$eligibilityKey]['resolveIds'] ?? null;

        return $resolver !== null ? $resolver($rawIds) : [];
    }

    /**
     * @return array<int, Component>
     */
    public function fields(): array
    {
        return array_values(array_map(fn (array $mode): Component => ($mode['field'])(), $this->modes));
    }

    /**
     * @return array<int, string>
     */
    public function formKeys(): array
    {
        return array_values(array_map(fn (array $mode): string => $mode['formKey'], $this->modes));
    }
}
