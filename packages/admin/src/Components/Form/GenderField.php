<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;

final class GenderField
{
    public static function make(): Component
    {
        return Select::make('gender')
            ->label(__('shopper::layout.forms.label.gender'))
            ->options([
                'male' => __('shopper::words.male'),
                'female' => __('shopper::words.female'),
            ])
            ->default('male')
            ->native(false);
    }
}
