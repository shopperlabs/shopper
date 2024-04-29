<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Filament\Forms\Components;
use Shopper\Core\Models\Country;

final class AddressField
{
    public static function getPrefix(?string $prefix = null): ?string
    {
        if (! $prefix) {
            return null;
        }

        return $prefix . '.';
    }

    public static function make(?string $prefix = null): array
    {
        return [
            Components\TextInput::make(self::getPrefix($prefix) . 'street_address')
                ->label(__('shopper::forms.label.street_address'))
                ->placeholder('Akwa Avenue 34...')
                ->columnSpan('full')
                ->required(),
            Components\TextInput::make(self::getPrefix($prefix) . 'street_address_plus')
                ->label(__('shopper::forms.label.street_address_plus'))
                ->columnSpan('full'),
            Components\TextInput::make(self::getPrefix($prefix) . 'city')
                ->label(__('shopper::forms.label.city'))
                ->required(),
            Components\TextInput::make(self::getPrefix($prefix) . 'zipcode')
                ->label(__('shopper::forms.label.postal_code'))
                ->required(),
            Components\Select::make(self::getPrefix($prefix) . 'country_id')
                ->label(__('shopper::forms.label.country'))
                ->options(Country::query()->pluck('name', 'id'))
                ->searchable()
                ->required()
                ->columnSpan('full'),
            Components\TextInput::make(self::getPrefix($prefix) . 'phone_number')
                ->label(__('shopper::forms.label.phone_number'))
                ->tel()
                ->columnSpan('full'),
        ];
    }
}
