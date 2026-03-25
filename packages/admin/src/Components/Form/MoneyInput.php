<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Closure;
use Filament\Forms\Components\TextInput;

class MoneyInput extends TextInput
{
    protected string|Closure|null $currency = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->numeric();

        $this->afterStateHydrated(function (self $component, $state): void {
            if ($state === null || $state === '') {
                return;
            }

            $currency = $component->getCurrency();

            $component->state(
                is_no_division_currency($currency) ? $state : (float) $state / 100
            );
        });

        $this->dehydrateStateUsing(function ($state): ?int {
            if ($state === null || $state === '') {
                return null;
            }

            $currency = $this->getCurrency();

            return is_no_division_currency($currency)
                ? (int) $state
                : (int) round((float) $state * 100);
        });

        $this->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2); // @phpstan-ignore method.notFound
    }

    public function currency(string|Closure $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getCurrency(): string
    {
        $currency = $this->evaluate($this->currency) ?? shopper_currency();

        if (is_no_division_currency($currency)) {
            $this->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 0); // @phpstan-ignore method.notFound
        }

        return $currency;
    }
}
