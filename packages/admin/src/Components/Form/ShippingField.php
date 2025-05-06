<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Filament\Forms;
use Shopper\Core\Enum\Dimension;

final class ShippingField
{
    /**
     * @return array<array-key, Forms\Components\Field>
     */
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
                        ->options(Dimension\Length::toArray())
                        ->default(Dimension\Length::CM)
                ),
            TextInputSelect::make('height_value')
                ->label(__('shopper::forms.label.height'))
                ->numeric()
                ->select(
                    fn () => Forms\Components\Select::make('height_unit')
                        ->selectablePlaceholder(false)
                        ->native(false)
                        ->options(Dimension\Length::toArray())
                        ->default(Dimension\Length::CM)
                ),
            TextInputSelect::make('weight_value')
                ->label(__('shopper::forms.label.weight'))
                ->numeric()
                ->select(
                    fn () => Forms\Components\Select::make('weight_unit')
                        ->selectablePlaceholder(false)
                        ->native(false)
                        ->options(Dimension\Weight::toArray())
                        ->default(Dimension\Weight::KG)
                ),
            TextInputSelect::make('volume_value')
                ->label(__('shopper::forms.label.volume'))
                ->numeric()
                ->select(
                    fn () => Forms\Components\Select::make('volume_unit')
                        ->selectablePlaceholder(false)
                        ->native(false)
                        ->options(Dimension\Volume::toArray())
                        ->default(Dimension\Volume::ML)
                ),
            TextInputSelect::make('depth_value')
                ->label(__('shopper::forms.label.depth'))
                ->numeric()
                ->select(
                    fn () => Forms\Components\Select::make('depth_unit')
                        ->selectablePlaceholder(false)
                        ->native(false)
                        ->options(Dimension\Length::toArray())
                        ->default(Dimension\Length::CM)
                ),
        ];
    }
}
