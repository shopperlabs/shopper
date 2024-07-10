<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

final class SeoField
{
    public static function make(): array
    {
        return [
            TextInput::make('seo_title')
                ->label(__('shopper::forms.label.title')),
            Textarea::make('seo_description')
                ->label(__('shopper::forms.label.description'))
                ->hint(__('shopper::words.seo.characters'))
                ->maxLength(160),
        ];
    }
}
