<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Component;
use Shopper\Core\Enum\Dimension;

final class ShippingField
{
    /**
     * @return array<array-key, Component>
     */
    public static function make(): array
    {
        return [
            TextInputSelect::make('width_value')
                ->label(__('shopper::forms.label.width'))
                ->numeric()
                ->select(
                    fn (): Select => Select::make('width_unit')
                        ->selectablePlaceholder(false)
                        ->native(false)
                        ->options(Dimension\Length::class)
                        ->default(Dimension\Length::CM)
                ),
            TextInputSelect::make('height_value')
                ->label(__('shopper::forms.label.height'))
                ->numeric()
                ->select(
                    fn (): Select => Select::make('height_unit')
                        ->selectablePlaceholder(false)
                        ->native(false)
                        ->options(Dimension\Length::class)
                        ->default(Dimension\Length::CM)
                ),
            TextInputSelect::make('weight_value')
                ->label(__('shopper::forms.label.weight'))
                ->numeric()
                ->select(
                    fn (): Select => Select::make('weight_unit')
                        ->selectablePlaceholder(false)
                        ->native(false)
                        ->options(Dimension\Weight::class)
                        ->default(Dimension\Weight::KG)
                ),
            TextInputSelect::make('volume_value')
                ->label(__('shopper::forms.label.volume'))
                ->numeric()
                ->select(
                    fn (): Select => Select::make('volume_unit')
                        ->selectablePlaceholder(false)
                        ->native(false)
                        ->options(Dimension\Volume::class)
                        ->default(Dimension\Volume::ML)
                ),
            TextInputSelect::make('depth_value')
                ->label(__('shopper::forms.label.depth'))
                ->numeric()
                ->select(
                    fn (): Select => Select::make('depth_unit')
                        ->selectablePlaceholder(false)
                        ->native(false)
                        ->options(Dimension\Length::class)
                        ->default(Dimension\Length::CM)
                ),
        ];
    }
}
