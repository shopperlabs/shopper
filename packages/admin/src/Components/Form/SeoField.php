<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Filament\Forms\Components;

final class SeoField
{
    /**
     * @return array<int, Components\Field>
     */
    public static function make(): array
    {
        return [
            Components\TextInput::make('seo_title')
                ->label(__('shopper::forms.label.title')),
            Components\Textarea::make('seo_description')
                ->label(__('shopper::forms.label.description'))
                ->hint(__('shopper::words.seo.characters'))
                ->maxLength(160),
        ];
    }
}
