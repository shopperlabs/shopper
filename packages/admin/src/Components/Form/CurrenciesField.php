<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Shopper\Core\Models\Currency;

final class CurrenciesField
{
    /**
     * @param  Collection<int, Currency>  $currencies
     * @return array<array-key, \Filament\Schemas\Components\Component>
     */
    public static function make(Collection $currencies): array
    {
        return $currencies
            ->map(fn (Currency $currency, int $index): Group => Group::make()
                ->schema([
                    TextEntry::make($currency->code)
                        ->label("{$currency->name} ({$currency->symbol})"),
                    Group::make()
                        ->schema([
                            MoneyInput::make('amount')  // @phpstan-ignore-line
                                ->label(__('shopper::forms.label.price_amount'))
                                ->helperText(__('shopper::pages/products.amount_price_help_text'))
                                ->statePath($currency->id.'.amount')
                                ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                ->required(fn (Get $get): bool => $get($currency->id.'.compare_amount') !== null)
                                ->suffix($currency->code)
                                ->currency($currency->code)
                                ->live(),
                            MoneyInput::make('compare_amount')  // @phpstan-ignore-line
                                ->label(__('shopper::forms.label.compare_price'))
                                ->helperText(__('shopper::pages/products.compare_price_help_text'))
                                ->statePath($currency->id.'.compare_amount')
                                ->afterStateUpdated(
                                    fn (?string $state, Set $set): mixed => $state ?? $set($currency->id.'.compare_amount', null)
                                )
                                ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                ->suffix($currency->code)
                                ->currency($currency->code)
                                ->live(),
                            MoneyInput::make('cost_amount')  // @phpstan-ignore-line
                                ->label(__('shopper::forms.label.cost_per_item'))
                                ->helperText(__('shopper::pages/products.cost_per_items_help_text'))
                                ->statePath($currency->id.'.cost_amount')
                                ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                ->suffix($currency->code)
                                ->currency($currency->code),
                        ])
                        ->columns(3),
                    TextEntry::make('placeholder')
                        ->hiddenLabel()
                        ->state(new HtmlString(
                            "<div class='py-2'><div class='border-t border-gray-100 dark:border-white/10'></div></div>"
                        ))
                        ->visible($index + 1 !== count(shopper_setting('currencies'))),
                ]))
            ->toArray();
    }
}
