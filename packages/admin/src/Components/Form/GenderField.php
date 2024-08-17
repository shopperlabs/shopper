<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
use Shopper\Core\Enum\GenderType;

final class GenderField
{
    public static function make(): Component
    {
        return Select::make('gender')
            ->label(__('shopper::forms.label.gender'))
            ->options(GenderType::class)
            ->default(GenderType::Male())
            ->native(false);
    }
}
