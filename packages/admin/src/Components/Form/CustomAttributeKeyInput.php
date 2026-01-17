<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Support\Components\Component;
use Shopper\Core\Enum\FieldType;

final class CustomAttributeKeyInput
{
    public static function make(string $key, FieldType $type): Component
    {
        return match ($type) {
            FieldType::ColorPicker => ColorPicker::make($key)
                ->default('#1E3A8A'),
            default => TextInput::make($key)
        };
    }
}
