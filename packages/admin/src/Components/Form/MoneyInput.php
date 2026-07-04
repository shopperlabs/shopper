<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Closure;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;
use NumberFormatter;

class MoneyInput extends TextInput
{
    protected string|Closure|null $currency = null;

    protected bool|Closure $isMoney = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->numeric()
            ->inlinePrefix()
            ->inlineSuffix();

        $this->suffix(fn (): ?string => $this->isMoney() ? $this->getCurrency() : null);

        $this->afterStateHydrated(function (self $component, $state): void {
            if ($state === null || $state === '' || ! $component->isMoney()) {
                return;
            }

            $currency = $component->getCurrency();

            $component->state(
                is_no_division_currency($currency) ? $state : (float) $state / 100
            );
        });

        $this->dehydrateStateUsing(function ($state) {
            if ($state === null || $state === '') {
                return null;
            }

            if (! $this->isMoney()) {
                return $state;
            }

            $currency = $this->getCurrency();

            return is_no_division_currency($currency)
                ? (int) $state
                : (int) round((float) $state * 100);
        });

        [$thousandSeparator, $decimalSeparator] = $this->getLocaleSeparators();

        $this->currencyMask(thousandSeparator: $thousandSeparator, decimalSeparator: $decimalSeparator, precision: 2); // @phpstan-ignore method.notFound
    }

    public function prefix(string|Htmlable|Closure|null $label, bool|Closure $isInline = true): static
    {
        return parent::prefix($label, $isInline);
    }

    public function suffix(string|Htmlable|Closure|null $label, bool|Closure $isInline = true): static
    {
        return parent::suffix($label, $isInline);
    }

    public function currency(string|Closure $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function money(bool|Closure $condition = true): static
    {
        $this->isMoney = $condition;

        return $this;
    }

    public function isMoney(): bool
    {
        return (bool) $this->evaluate($this->isMoney);
    }

    public function getCurrency(): string
    {
        $currency = $this->evaluate($this->currency) ?? shopper_currency();

        if (is_no_division_currency($currency)) {
            [$thousandSeparator, $decimalSeparator] = $this->getLocaleSeparators();

            $this->currencyMask(thousandSeparator: $thousandSeparator, decimalSeparator: $decimalSeparator, precision: 0); // @phpstan-ignore method.notFound
        }

        return $currency;
    }

    /**
     * @return array{string, string}
     */
    private function getLocaleSeparators(): array
    {
        if (! class_exists(NumberFormatter::class)) {
            return [',', '.'];
        }

        $formatter = new NumberFormatter(app()->getLocale(), NumberFormatter::DECIMAL);

        $decimalSeparator = $formatter->getSymbol(NumberFormatter::DECIMAL_SEPARATOR_SYMBOL) ?: '.';
        $thousandSeparator = $formatter->getSymbol(NumberFormatter::GROUPING_SEPARATOR_SYMBOL) ?: ',';

        if (preg_match('/^\s$/u', $thousandSeparator)) {
            $thousandSeparator = ' ';
        }

        return [$thousandSeparator, $decimalSeparator];
    }
}
