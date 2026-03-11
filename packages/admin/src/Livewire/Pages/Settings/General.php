<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Components\Section;
use Shopper\Components\Separator;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Setting;
use Shopper\Traits\HandlesAuthorizationExceptions;
use Shopper\Traits\SaveSettings;

/**
 * @property-read Schema $form
 */
#[Layout('shopper::components.layouts.setting')]
class General extends Component implements HasActions, HasForms
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithForms;
    use SaveSettings;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->authorize('access_setting');

        /** @var Collection<int, Setting> $settings */
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
            'state',
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
                fn (Setting $item): array => [$item['key'] => $item['value']]
            )->toArray()
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('shopper::pages/settings/global.general.store_details'))
                    ->aside()
                    ->compact()
                    ->description(__('shopper::pages/settings/global.general.store_detail_summary'))
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->schema([
                        TextInput::make('name')
                            ->label(__('shopper::forms.label.store_name'))
                            ->prefixIcon(Untitledui::Shop)
                            ->inlinePrefix()
                            ->maxLength(100)
                            ->required(),
                        Grid::make()
                            ->schema([
                                TextInput::make('email')
                                    ->label(__('shopper::forms.label.email'))
                                    ->prefixIcon(Untitledui::Mail)
                                    ->inlinePrefix()
                                    ->helperText(__('shopper::pages/settings/global.general.email_helper'))
                                    ->autocomplete('email-address')
                                    ->email()
                                    ->required(),
                                TextInput::make('phone_number')
                                    ->label(__('shopper::forms.label.phone_number'))
                                    ->tel()
                                    ->helperText(__('shopper::pages/settings/global.general.phone_number_helper')),
                            ]),
                    ]),
                Separator::make(),
                Section::make(__('shopper::pages/settings/global.general.assets'))
                    ->aside()
                    ->compact()
                    ->description(__('shopper::pages/settings/global.general.assets_summary'))
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->schema([
                        FileUpload::make('logo')
                            ->label(__('shopper::forms.label.logo'))
                            ->avatar()
                            ->image()
                            ->maxSize(1024)
                            ->disk(config('shopper.media.storage.collection_name')),
                        FileUpload::make('cover')
                            ->label(__('shopper::forms.label.cover_photo'))
                            ->image()
                            ->maxSize(1024)
                            ->disk(config('shopper.media.storage.collection_name')),
                    ]),
                Separator::make(),
                Section::make(__('shopper::pages/settings/global.general.store_address'))
                    ->aside()
                    ->compact()
                    ->description(__('shopper::pages/settings/global.general.store_address_summary'))
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->schema([
                        TextInput::make('legal_name')
                            ->label(__('shopper::forms.label.legal_name'))
                            ->placeholder('ShopStation LLC')
                            ->required(),
                        RichEditor::make('about')
                            ->label(__('shopper::forms.label.about'))
                            ->fileAttachmentsDisk(config('shopper.media.storage.disk_name'))
                            ->fileAttachmentsDirectory(config('shopper.media.storage.collection_name')),
                        TextInput::make('street_address')
                            ->label(__('shopper::forms.label.street_address'))
                            ->placeholder('Akwa Avenue 34')
                            ->required(),
                        Grid::make()->schema([
                            TextInput::make('city')
                                ->label(__('shopper::forms.label.city'))
                                ->required(),
                            TextInput::make('postal_code')
                                ->label(__('shopper::forms.label.postal_code'))
                                ->required(),
                            TextInput::make('state')
                                ->label(__('shopper::forms.label.state')),
                            Select::make('country_id')
                                ->label(__('shopper::forms.label.country'))
                                ->options(Country::query()->pluck('name', 'id'))
                                ->searchable(),
                        ]),
                    ]),
                Separator::make(),
                Section::make(__('shopper::pages/settings/global.general.store_currency'))
                    ->aside()
                    ->compact()
                    ->description(__('shopper::pages/onboarding.currency_description'))
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->schema([
                        Select::make('currencies')
                            ->label(__('shopper::forms.label.currencies'))
                            ->helperText(__('shopper::pages/onboarding.currencies_description'))
                            ->options(Currency::query()->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->multiple()
                            ->minItems(1)
                            ->required()
                            ->live()
                            ->native(false),
                        Select::make('default_currency_id')
                            ->label(__('shopper::forms.label.default_currency'))
                            ->options(
                                fn (Get $get) => Currency::query()
                                    ->select('name', 'id')
                                    ->whereIn('id', $get('currencies'))
                                    ->pluck('name', 'id')
                            )
                            ->native(false)
                            ->required(),
                    ]),
                Separator::make(),
                Section::make(__('shopper::pages/settings/global.general.social_links'))
                    ->aside()
                    ->compact()
                    ->description(__('shopper::pages/settings/global.general.social_links_summary'))
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->schema([
                        TextInput::make('facebook_link')
                            ->prefix(
                                fn (): HtmlString => new HtmlString(Blade::render(<<<'Blade'
                                    <x-shopper::icons.facebook
                                        class="size-5 text-gray-400 dark:text-gray-500"
                                        aria-hidden="true"
                                    />
                                Blade))
                            )
                            ->inlinePrefix()
                            ->label(__('shopper::words.socials.facebook'))
                            ->placeholder('https://facebook.com/laravelshopper'),
                        Grid::make()
                            ->schema([
                                TextInput::make('instagram_link')
                                    ->prefix(
                                        fn (): HtmlString => new HtmlString(Blade::render(<<<'Blade'
                                            <x-shopper::icons.instagram
                                                class="size-5 text-gray-400 dark:text-gray-500"
                                                aria-hidden="true"
                                            />
                                        Blade))
                                    )
                                    ->inlinePrefix()
                                    ->label(__('shopper::words.socials.instagram'))
                                    ->placeholder('https://instagram.com/laravelshopper'),
                                TextInput::make('twitter_link')
                                    ->prefix(
                                        fn (): HtmlString => new HtmlString(Blade::render(<<<'Blade'
                                            <x-shopper::icons.twitter
                                                class="size-4 text-gray-400 dark:text-gray-500"
                                                aria-hidden="true"
                                            />
                                        Blade))
                                    )
                                    ->inlinePrefix()
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

        Notification::make()
            ->title(__('shopper::notifications.store_info'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.general')
            ->title(__('shopper::pages/settings/global.general.title'));
    }
}
