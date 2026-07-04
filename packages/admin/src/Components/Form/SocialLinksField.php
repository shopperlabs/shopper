<?php

declare(strict_types=1);

namespace Shopper\Components\Form;

use Closure;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Shopper\Core\Enum\SocialPlatform;

class SocialLinksField extends Repeater
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->hiddenLabel()
            ->table([
                TableColumn::make(__('shopper::pages/settings/global.general.social_links'))
                    ->hiddenHeaderLabel()
                    ->width('220px'),
                TableColumn::make(__('shopper::forms.label.url'))
                    ->hiddenHeaderLabel(),
            ])
            ->schema([
                Select::make('platform')
                    ->hiddenLabel()
                    ->options($this->getPlatformOptions())
                    ->allowHtml()
                    ->native(false)
                    ->selectablePlaceholder(false)
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->disabled(fn (Get $get): bool => filled($get('platform')))
                    ->dehydrated()
                    ->required(),
                TextInput::make('url')
                    ->hiddenLabel()
                    ->placeholder('https://...')
                    ->url()
                    ->required()
                    ->rule(fn (Get $get): Closure => function (string $attribute, mixed $value, Closure $fail) use ($get): void {
                        $platform = SocialPlatform::tryFrom((string) $get('platform'));

                        if (! $platform || blank($value) || $platform->matchesUrl((string) $value)) {
                            return;
                        }

                        $fail('shopper-core::validation.social_url')->translate([
                            'platform' => $platform->getLabel(),
                        ]);
                    }),
            ])
            ->reorderable()
            ->defaultItems(0)
            ->maxItems(count(SocialPlatform::cases()))
            ->addActionLabel(__('shopper::pages/settings/global.general.add_social_link'));
    }

    /**
     * @return array<string, string>
     */
    private function getPlatformOptions(): array
    {
        return collect(SocialPlatform::cases())
            ->mapWithKeys(fn (SocialPlatform $platform): array => [
                $platform->value => '<span class="flex items-center gap-2">'
                    .svg($platform->getIcon(), 'size-4 shrink-0')->toHtml()
                    .'<span>'.e($platform->getLabel()).'</span></span>',
            ])
            ->all();
    }
}
