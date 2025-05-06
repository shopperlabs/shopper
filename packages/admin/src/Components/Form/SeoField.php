<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Filament\Forms;

final class SeoField
{
    /**
     * @return array<array-key, Forms\Components\Field>
     */
    public static function make(): array
    {
        return [
            Forms\Components\TextInput::make('seo_title')
                ->label(__('shopper::forms.label.title')),
            Forms\Components\Textarea::make('seo_description')
                ->label(__('shopper::forms.label.description'))
                ->hint(__('shopper::words.seo.characters'))
                ->maxLength(160),
        ];
    }
}
