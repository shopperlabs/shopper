<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Filament\Forms\Components;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Shopper\Components\Section;
use Shopper\Components\Separator;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Setting;
use Shopper\Traits\SaveSettings;

#[Layout('shopper::components.layouts.setting')]
class General extends Component implements HasForms
{
    use InteractsWithForms;
    use SaveSettings;

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::query()->whereIn('key', [
            'name',
            'legal_name',
            'email',
            'about',
            'phone_number',
            'logo',
            'cover',
            'street_address',
            'postal_code',
            'city',
            'country_id',
            'default_currency_id',
            'currencies',
            'facebook_link',
            'instagram_link',
            'twitter_link',
        ])
            ->select('value', 'key')
            ->get();

        $this->form->fill(
            $settings->mapWithKeys(
                fn (Setting $item) => [$item['key'] => $item['value']]
            )->toArray()
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('shopper::pages/settings.settings.store_details'))
                    ->description(__('shopper::pages/settings.settings.store_detail_summary'))
                    ->aside()
                    ->compact()
                    ->schema([
                        Components\TextInput::make('name')
                            ->label(__('shopper::forms.label.store_name'))
                            ->prefixIcon('untitledui-shop')
                            ->maxLength(100)
                            ->required(),
                        Components\Grid::make()
                            ->schema([
                                Components\TextInput::make('email')
                                    ->label(__('shopper::forms.label.email'))
                                    ->prefixIcon('untitledui-mail')
                                    ->helperText(__('shopper::pages/settings.settings.email_helper'))
                                    ->autocomplete('email-address')
                                    ->email()
                                    ->required(),
                                Components\TextInput::make('phone_number')
                                    ->label(__('shopper::forms.label.phone_number'))
                                    ->tel()
                                    ->helperText(__('shopper::pages/settings.settings.phone_number_helper')),
                            ]),
                    ]),
                Separator::make(),
                Section::make(__('shopper::pages/settings.settings.assets'))
                    ->description(__('shopper::pages/settings.settings.assets_summary'))
                    ->aside()
                    ->compact()
                    ->schema([
                        Components\FileUpload::make('logo')
                            ->label(__('shopper::layout.forms.label.logo'))
                            ->avatar()
                            ->image()
                            ->maxSize(1024)
                            ->disk(config('shopper.core.storage.collection_name')),
                        Components\FileUpload::make('cover')
                            ->label(__('shopper::layout.forms.label.cover_photo'))
                            ->image()
                            ->maxSize(1024)
                            ->disk(config('shopper.core.storage.collection_name')),
                    ]),
                Separator::make(),
                Section::make(__('shopper::pages/settings.settings.store_address'))
                    ->description(__('shopper::pages/settings.settings.store_address_summary'))
                    ->aside()
                    ->compact()
                    ->schema([
                        Components\TextInput::make('legal_name')
                            ->label(__('shopper::layout.forms.label.legal_name'))
                            ->placeholder('ShopStation LLC')
                            ->required(),
                        Components\RichEditor::make('about')
                            ->label(__('shopper::forms.label.about'))
                            ->fileAttachmentsDisk(config('shopper.core.storage.disk_name'))
                            ->fileAttachmentsDirectory(config('shopper.core.storage.collection_name')),
                        Components\TextInput::make('street_address')
                            ->label(__('shopper::layout.forms.label.street_address'))
                            ->placeholder('Akwa Avenue 34')
                            ->required(),
                        Components\Grid::make()->schema([
                            Components\TextInput::make('city')
                                ->label(__('shopper::layout.forms.label.city'))
                                ->required(),
                            Components\TextInput::make('postal_code')
                                ->label(__('shopper::layout.forms.label.postal_code'))
                                ->required(),
                        ]),
                        Components\Select::make('country_id')
                            ->label(__('shopper::layout.forms.label.country'))
                            ->options(Country::query()->pluck('name', 'id'))
                            ->searchable(),
                    ]),
                Separator::make(),
                Section::make(__('shopper::pages/settings.settings.store_currency'))
                    ->description(__('shopper::pages/settings.settings.store_currency_summary'))
                    ->aside()
                    ->compact()
                    ->schema([
                        Components\Select::make('currency_id')
                            ->label(__('shopper::layout.forms.label.currency'))
                            ->options(Currency::query()->pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                    ]),
                Separator::make(),
                Section::make(__('shopper::pages/settings.settings.social_links'))
                    ->description(__('shopper::pages/settings.settings.social_links_summary'))
                    ->aside()
                    ->compact()
                    ->schema([
                        Components\TextInput::make('facebook_link')
                            ->prefix(
                                fn (): HtmlString => new HtmlString(Blade::render(<<<Blade
                                    <x-shopper::icons.facebook
                                        class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                        aria-hidden="true"
                                    />
                                Blade))
                            )
                            ->label(__('shopper::words.socials.facebook'))
                            ->placeholder('https://facebook.com/laravelshopper'),
                        Components\Grid::make()
                            ->schema([
                                Components\TextInput::make('instagram_link')
                                    ->prefix(
                                        fn (): HtmlString => new HtmlString(Blade::render(<<<Blade
                                            <x-shopper::icons.instagram
                                                class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                                aria-hidden="true"
                                            />
                                        Blade))
                                    )
                                    ->label(__('shopper::words.socials.instagram'))
                                    ->placeholder('https://instagram.com/laravelshopper'),
                                Components\TextInput::make('twitter_link')
                                    ->prefix(
                                        fn (): HtmlString => new HtmlString(Blade::render(<<<Blade
                                            <x-shopper::icons.twitter
                                                class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                                aria-hidden="true"
                                            />
                                        Blade))
                                    )
                                    ->label(__('shopper::words.socials.twitter'))
                                    ->placeholder('https://twitter.com/laravelshopper'),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function store(): void
    {
        $this->saveSettings($this->form->getState());

        Cache::forget('shopper-setting-default_currency_id');

        Notification::make()
            ->title(__('shopper::notifications.store_info'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.general')
            ->title(__('shopper::pages/settings.settings.title'));
    }
}
