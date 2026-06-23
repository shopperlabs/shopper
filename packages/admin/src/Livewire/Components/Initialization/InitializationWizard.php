<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Initialization;

use Closure;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\View;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Components\OnboardingWizard;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Setting;
use Shopper\Models\Contracts\ShopperUser;
use Shopper\Traits\SaveSettings;

/**
 * @property-read Schema $form
 */
final class InitializationWizard extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use SaveSettings;

    private const array ALLOWED_KEYS = [
        'name',
        'email',
        'country_id',
        'currencies',
        'default_currency_id',
        'street_address',
        'postal_code',
        'state',
        'city',
        'phone_number',
        'facebook_link',
        'instagram_link',
        'twitter_link',
    ];

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    #[Locked]
    public array $countryOptions = [];

    #[Locked]
    public array $currencyOptions = [];

    public function mount(): void
    {
        $this->authorizeOnboarding();

        $this->countryOptions = self::countryOptions();
        $this->currencyOptions = self::currencyOptions();

        /** @var Collection<int, Setting> $settings */
        $settings = Setting::query()
            ->whereIn('key', [
                'name',
                'email',
                'country_id',
                'default_currency_id',
                'currencies',
                'street_address',
                'city',
                'state',
                'postal_code',
                'phone_number',
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
                OnboardingWizard::make([
                    Wizard\Step::make(__('shopper::pages/onboarding.step_one_title'))
                        ->schema([
                            View::make('shopper::livewire.components.initialization.partials.step-header')
                                ->viewData([
                                    'icon' => 'untitledui-heading-02',
                                    'stepLabel' => __('shopper::pages/onboarding.step_1'),
                                    'title' => __('shopper::pages/onboarding.tell_about'),
                                    'description' => __('shopper::pages/onboarding.step_1_description'),
                                    'optional' => null,
                                ]),
                            Grid::make()
                                ->schema([
                                    TextInput::make('name')
                                        ->label(__('shopper::forms.label.store_name'))
                                        ->prefixIcon(Untitledui::Shop)
                                        ->inlinePrefix()
                                        ->maxLength(100)
                                        ->required(),
                                    TextInput::make('email')
                                        ->label(__('shopper::forms.label.email'))
                                        ->prefixIcon(Untitledui::Mail)
                                        ->inlinePrefix()
                                        ->autocomplete('email-address')
                                        ->placeholder('your@shopper.store')
                                        ->email()
                                        ->required(),
                                ]),
                            Select::make('country_id')
                                ->label(__('shopper::forms.label.country'))
                                ->options($this->countryOptions)
                                ->searchable()
                                ->native(false),
                            Select::make('currencies')
                                ->label(__('shopper::forms.label.currencies'))
                                ->helperText(__('shopper::pages/onboarding.currencies_description'))
                                ->options($this->currencyOptions)
                                ->searchable()
                                ->multiple()
                                ->minItems(1)
                                ->required()
                                ->live()
                                ->native(false),
                            Select::make('default_currency_id')
                                ->label(__('shopper::forms.label.default_currency'))
                                ->helperText(__('shopper::pages/onboarding.currency_description'))
                                ->placeholder(__('shopper::forms.placeholder.select_currencies'))
                                ->options(
                                    fn (Get $get): array => collect($this->currencyOptions)
                                        ->only($get('currencies') ?? [])
                                        ->all()
                                )
                                ->native(false)
                                ->required()
                                ->rules([
                                    fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get): void {
                                        $currencies = $get('currencies') ?? [];

                                        if (filled($value) && ! in_array($value, $currencies, false)) {
                                            $fail(__('shopper::pages/onboarding.default_currency_must_be_in_currencies'));
                                        }
                                    },
                                ]),
                        ])
                        ->afterValidation(function (): void {
                            $this->persistStep([
                                'name',
                                'email',
                                'country_id',
                                'currencies',
                                'default_currency_id',
                            ]);
                        }),
                    Wizard\Step::make(__('shopper::pages/onboarding.step_two_title'))
                        ->schema([
                            View::make('shopper::livewire.components.initialization.partials.step-header')
                                ->viewData([
                                    'icon' => 'untitledui-globe-05',
                                    'stepLabel' => __('shopper::pages/onboarding.step_2'),
                                    'title' => __('shopper::pages/onboarding.address_description'),
                                    'description' => __('shopper::pages/onboarding.step_2_description'),
                                    'optional' => null,
                                ]),
                            Grid::make(3)
                                ->schema([
                                    TextInput::make('street_address')
                                        ->label(__('shopper::forms.label.street_address'))
                                        ->placeholder('Akwa Avenue 34')
                                        ->columnSpan(['lg' => 2])
                                        ->maxLength(255)
                                        ->required(),
                                    TextInput::make('postal_code')
                                        ->label(__('shopper::forms.label.postal_code'))
                                        ->placeholder('00237')
                                        ->maxLength(20)
                                        ->required(),
                                    TextInput::make('state')
                                        ->label(__('shopper::forms.label.state'))
                                        ->placeholder('Littoral')
                                        ->columnSpan(['lg' => 2])
                                        ->maxLength(100),
                                    TextInput::make('city')
                                        ->label(__('shopper::forms.label.city'))
                                        ->maxLength(100)
                                        ->required(),
                                    TextInput::make('phone_number')
                                        ->label(__('shopper::forms.label.phone_number'))
                                        ->columnSpan(['lg' => 2])
                                        ->maxLength(30),
                                ]),
                        ])
                        ->afterValidation(function (): void {
                            $this->persistStep([
                                'street_address',
                                'postal_code',
                                'state',
                                'city',
                                'phone_number',
                            ]);
                        }),
                    Wizard\Step::make(__('shopper::pages/onboarding.step_tree_title'))
                        ->schema([
                            View::make('shopper::livewire.components.initialization.partials.step-header')
                                ->viewData([
                                    'icon' => 'untitledui-share-07',
                                    'stepLabel' => __('shopper::pages/onboarding.step_3'),
                                    'title' => __('shopper::pages/onboarding.social_description'),
                                    'description' => __('shopper::pages/onboarding.step_3_description'),
                                    'optional' => __('shopper::forms.label.optional'),
                                ]),
                            TextInput::make('facebook_link')
                                ->prefix($this->socialIconPrefix('facebook'))
                                ->inlinePrefix()
                                ->label(__('shopper::words.socials.facebook'))
                                ->placeholder('https://facebook.com/laravelshopper')
                                ->url()
                                ->maxLength(255),
                            TextInput::make('instagram_link')
                                ->prefix($this->socialIconPrefix('instagram'))
                                ->inlinePrefix()
                                ->label(__('shopper::words.socials.instagram'))
                                ->placeholder('https://instagram.com/laravelshopper')
                                ->url()
                                ->maxLength(255),
                            TextInput::make('twitter_link')
                                ->prefix($this->socialIconPrefix('twitter'))
                                ->inlinePrefix()
                                ->label(__('shopper::words.socials.twitter'))
                                ->placeholder('https://twitter.com/laravelshopper')
                                ->url()
                                ->maxLength(255),
                        ]),
                ])
                    ->contained(false)
                    ->nextAction(
                        fn (Action $action): Action => $action
                            ->label(__('shopper::forms.actions.next'))
                            ->icon(null)
                    )
                    ->previousAction(
                        fn (Action $action): Action => $action->label(__('shopper::forms.actions.back'))
                    )
                    ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                        <x-filament::button type="submit" wire:loading.attr="disabled">
                            <x-shopper::loader wire:loading wire:target="save" class="text-white" />
                            {{ __('shopper::pages/onboarding.action') }}
                        </x-filament::button>
                    BLADE))),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $this->authorizeOnboarding();

        $state = array_intersect_key(
            $this->form->getState(),
            array_flip(self::ALLOWED_KEYS),
        );

        DB::transaction(function () use ($state): void {
            $this->saveSettings($state);

            $this->createDefaultInventory($state);
        });

        Notification::make()
            ->title(__('shopper::notifications.store_info'))
            ->success()
            ->send();

        $this->redirectRoute('shopper.dashboard', navigate: true);
    }

    public function render(): ViewContract
    {
        return view('shopper::livewire.components.initialization.wizard');
    }

    /**
     * @return array<int|string, string|array<int|string, string>>
     */
    private static function countryOptions(): array
    {
        return Cache::remember(
            'shopper.onboarding.country_options.'.app()->getLocale(),
            now()->addDay(),
            fn (): array => Country::query()
                ->select('name', 'id', 'region', 'cca2')
                ->orderBy('name')
                ->get()
                ->sortBy('region')
                ->groupBy('region')
                ->map(fn ($region): array => $region->pluck('name', 'id')->toArray())
                ->all()
        );
    }

    /**
     * @return array<int|string, string>
     */
    private static function currencyOptions(): array
    {
        return Cache::remember(
            'shopper.onboarding.currency_options.'.app()->getLocale(),
            now()->addDay(),
            fn (): array => Currency::query()
                ->orderBy('name')
                ->pluck('name', 'id')
                ->all()
        );
    }

    private function socialIconPrefix(string $name): HtmlString
    {
        return new HtmlString(Blade::render(<<<'Blade'
            <x-dynamic-component
                :component="'shopper::icons.' . $name"
                class="size-5 text-gray-400 dark:text-gray-500"
                aria-hidden="true"
            />
        Blade, ['name' => $name]));
    }

    /**
     * @param  array<int, string>  $keys
     */
    private function persistStep(array $keys): void
    {
        $this->authorizeOnboarding();

        $allowed = array_intersect($keys, self::ALLOWED_KEYS);

        $values = collect($this->data ?? [])
            ->only($allowed)
            ->all();

        $this->saveSettings($values, locked: false);
    }

    private function authorizeOnboarding(): void
    {
        $user = shopper()->auth()->user();

        abort_unless(
            $user instanceof ShopperUser
                && ($user->isAdmin() || $user->can('access_setting')),
            403,
        );
    }

    /**
     * @param  array<string, mixed>  $state
     */
    private function createDefaultInventory(array $state): void
    {
        Inventory::query()->updateOrCreate(
            ['is_default' => true],
            [
                'name' => $state['name'],
                'code' => Str::slug((string) $state['name']),
                'email' => $state['email'],
                'street_address' => $state['street_address'],
                'postal_code' => $state['postal_code'],
                'city' => $state['city'],
                'phone_number' => $state['phone_number'] ?? null,
                'country_id' => $state['country_id'] ?? null,
            ]
        );
    }
}
