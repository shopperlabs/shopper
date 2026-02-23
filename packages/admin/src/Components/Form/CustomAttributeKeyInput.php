<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;
use Shopper\Core\Enum\FieldType;

final class CustomAttributeKeyInput
{
    public static function make(string $key, FieldType $type): Field
    {
        return match ($type) {
            FieldType::ColorPicker => ColorPicker::make($key)
                ->default('#1E3A8A'),
            default => TextInput::make($key)
        };
    }
}
