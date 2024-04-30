<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Filament\Forms;
use Shopper\Core\Enum\Dimension\Length;
use Shopper\Core\Enum\Dimension\Volume;
use Shopper\Core\Enum\Dimension\Weight;

final class ShippingField
{
    public static function make(): array
    {
        return [
            TextInputSelect::make('width_value')
                ->label(__('shopper::forms.label.width'))
                ->numeric()
                ->select(
                    fn () => Forms\Components\Select::make('width_unit')
                        ->selectablePlaceholder(false)
                        ->native(false)
                        ->options(Length::toArray())
                        ->default(Length::CM)
                ),

            TextInputSelect::make('height_value')
                ->label(__('shopper::forms.label.height'))
                ->numeric()
                ->select(
                    fn () => Forms\Components\Select::make('height_unit')
                        ->selectablePlaceholder(false)
                        ->native(false)
                        ->options(Length::toArray())
                        ->default(Length::CM)
                ),

            TextInputSelect::make('weight_value')
                ->label(__('shopper::forms.label.weight'))
                ->numeric()
                ->select(
                    fn () => Forms\Components\Select::make('weight_unit')
                        ->selectablePlaceholder(false)
                        ->native(false)
                        ->options(Weight::toArray())
                        ->default(Weight::KG)
                ),

            TextInputSelect::make('volume_value')
                ->label(__('shopper::forms.label.volume'))
                ->numeric()
                ->select(
                    fn () => Forms\Components\Select::make('volume_unit')
                        ->selectablePlaceholder(false)
                        ->native(false)
                        ->options(Volume::toArray())
                        ->default(Volume::ML)
                ),
        ];
    }
}
