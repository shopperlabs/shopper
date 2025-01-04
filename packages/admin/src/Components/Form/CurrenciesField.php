<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Filament\Forms;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Shopper\Core\Models\Currency;

final class CurrenciesField
{
    public static function make(Collection $currencies): array
    {
        return $currencies
            ->map(fn (Currency $currency, $index): Forms\Components\Group => Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Placeholder::make($currency->code)
                        ->label("{$currency->name} ({$currency->symbol})"),
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\TextInput::make('amount')  // @phpstan-ignore-line
                                ->label(__('shopper::forms.label.price_amount'))
                                ->helperText(__('shopper::pages/products.amount_price_help_text'))
                                ->statePath($currency->id . '.amount')
                                ->numeric()
                                ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                ->required(fn (Forms\Get $get) => $get($currency->id . '.compare_amount') !== null)
                                ->suffix($currency->code)
                                ->live()
                                ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2),
                            Forms\Components\TextInput::make('compare_amount')  // @phpstan-ignore-line
                                ->label(__('shopper::forms.label.compare_price'))
                                ->helperText(__('shopper::pages/products.compare_price_help_text'))
                                ->statePath($currency->id . '.compare_amount')
                                ->afterStateUpdated(
                                    fn (?string $state, Forms\Set $set) => $state ?? $set($currency->id . '.compare_amount', null)
                                )
                                ->numeric()
                                ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                ->suffix($currency->code)
                                ->live()
                                ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2),
                            Forms\Components\TextInput::make('cost_amount')  // @phpstan-ignore-line
                                ->label(__('shopper::forms.label.cost_per_item'))
                                ->helperText(__('shopper::pages/products.cost_per_items_help_text'))
                                ->statePath($currency->id . '.cost_amount')
                                ->numeric()
                                ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                ->suffix($currency->code)
                                ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2),
                        ])
                        ->columns(3),
                    Forms\Components\Placeholder::make('')
                        ->content(new HtmlString(
                            "<div class='py-2'><div class='border-t border-gray-100 dark:border-white/10'></div></div>"
                        ))
                        ->visible($index + 1 !== count(shopper_setting('currencies'))),
                ]))
            ->toArray();
    }
}
