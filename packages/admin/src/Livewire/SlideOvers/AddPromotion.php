<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\View as SchemaView;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Laravelcm\LivewireSlideOvers\SlideOverComponent;
use Livewire\Attributes\Locked;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Actions\Store\SaveAndDispatchDiscountAction;
use Shopper\Cart\Discounts\DiscountEligibilityManager;
use Shopper\Components\Section;
use Shopper\Components\SlideOverWizard;
use Shopper\Components\Wizard\StepColumn;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Enum\ExclusivityClass;
use Shopper\Core\Enum\PromotionSource;
use Shopper\Core\Models\Campaign;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Zone;
use Shopper\Discounts\DiscountEligibilityFieldRegistry;
use Shopper\Livewire\Concerns\InteractsWithDiscountItems;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Schema $form
 */
class AddPromotion extends SlideOverComponent implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithDiscountItems;
    use InteractsWithSchemas;

    #[Locked]
    public Discount $discount;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    protected ?Zone $cachedZone = null;

    protected ?int $cachedZoneId = null;

    public static function panelMaxWidth(): string
    {
        return '3xl';
    }

    public function mount(): void
    {
        $this->authorize('discounts.create');

        $this->discount = new Discount;

        $this->form->fill([
            'type' => DiscountType::Percentage->value,
            'apply_to' => DiscountApplyTo::Order->value,
            'trigger' => PromotionSource::Code->value,
            'eligibility' => DiscountEligibility::Everyone->value,
            'min_required' => DiscountRequirement::None->value,
            'exclusivity_class' => ExclusivityClass::Order->value,
            'combinable' => true,
            'priority' => 0,
            'is_active' => true,
            'start_at' => now(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SlideOverWizard::make([
                    StepColumn::make(__('shopper::pages/discounts.wizard.type'))
                        ->icon(Untitledui::Coins)
                        ->schema([
                            Radio::make('type')
                                ->label(__('shopper::forms.label.type'))
                                ->options(DiscountType::options())
                                ->descriptions([
                                    DiscountType::Percentage->value => __('shopper::pages/discounts.type_percentage_description'),
                                    DiscountType::FixedAmount->value => __('shopper::pages/discounts.type_fixed_description'),
                                ])
                                ->columns()
                                ->columnSpanFull()
                                ->required()
                                ->live(),
                            TextInput::make('value')
                                ->label(fn (Get $get): ?string => match ($get('type')) {
                                    DiscountType::Percentage->value => __('shopper::pages/discounts.percentage'),
                                    DiscountType::FixedAmount->value => __('shopper::pages/discounts.fixed_amount'),
                                    default => null,
                                })
                                ->suffix(fn (Get $get): ?string => match ($get('type')) {
                                    DiscountType::Percentage->value => '%',
                                    DiscountType::FixedAmount->value => $this->resolveZoneCurrency($get('zone_id')),
                                    default => null,
                                })
                                ->dehydrateStateUsing(function (Get $get, $state) {
                                    if ($get('type') !== DiscountType::FixedAmount->value) {
                                        return (int) $state;
                                    }

                                    $currency = $this->resolveZoneCurrency($get('zone_id'));

                                    return is_no_division_currency($currency) ? (int) $state : (int) round((float) $state * 100);
                                })
                                ->numeric()
                                ->minValue(fn (Get $get): float => $get('type') === DiscountType::FixedAmount->value ? 0.01 : 1)
                                ->maxValue(fn (Get $get): int => $get('type') === DiscountType::Percentage->value ? 100 : 999_999_999)
                                ->required()
                                ->live(onBlur: true),
                            Radio::make('trigger')
                                ->label(__('shopper::pages/discounts.method'))
                                ->options([
                                    PromotionSource::Code->value => __('shopper::pages/discounts.method_code'),
                                    PromotionSource::Automatic->value => __('shopper::pages/discounts.method_automatic'),
                                ])
                                ->descriptions([
                                    PromotionSource::Code->value => __('shopper::pages/discounts.method_code_description'),
                                    PromotionSource::Automatic->value => __('shopper::pages/discounts.method_automatic_description'),
                                ])
                                ->columns()
                                ->columnSpanFull()
                                ->required()
                                ->live(),
                            TextInput::make('code')
                                ->label(__('shopper::forms.label.code'))
                                ->placeholder('CMR_SUMMER_900')
                                ->helperText(__('shopper::pages/discounts.name_helptext'))
                                ->hintAction(
                                    Action::make(__('shopper::words.generate'))
                                        ->color('info')
                                        ->action(fn (Set $set): mixed => $set('code', mb_substr(mb_strtoupper(uniqid(Str::random(10))), 0, 10))),
                                )
                                ->unique(table: Discount::class, column: 'code', ignoreRecord: true)
                                ->required(fn (Get $get): bool => $get('trigger') !== PromotionSource::Automatic->value)
                                ->visible(fn (Get $get): bool => $get('trigger') !== PromotionSource::Automatic->value)
                                ->live(onBlur: true),
                            Select::make('zone_id')
                                ->label(__('shopper::pages/settings/zones.single'))
                                ->relationship('zone', 'name')
                                ->native(false)
                                ->hint(__('shopper::forms.label.optional'))
                                ->live(),
                            Toggle::make('is_active')
                                ->label(__('shopper::forms.label.visibility'))
                                ->helperText(__('shopper::words.set_visibility', ['name' => __('shopper::pages/discounts.single')]))
                                ->columnSpanFull(),
                        ])
                        ->columns(),
                    StepColumn::make(__('shopper::words.conditions'))
                        ->icon(Untitledui::File02)
                        ->schema([
                            Radio::make('apply_to')
                                ->label(__('shopper::pages/discounts.applies_to'))
                                ->options(DiscountApplyTo::options())
                                ->descriptions([
                                    DiscountApplyTo::Order->value => __('shopper::pages/discounts.apply_to_order_description'),
                                    DiscountApplyTo::Products->value => __('shopper::pages/discounts.apply_to_products_description'),
                                ])
                                ->columns(2)
                                ->columnSpanFull()
                                ->required()
                                ->live(),
                            SchemaView::make('shopper::livewire.pages.discounts.partials.items-list')
                                ->viewData(['type' => 'products'])
                                ->visible(fn (Get $get): bool => $get('apply_to') === DiscountApplyTo::Products->value),
                            Radio::make('eligibility')
                                ->label(__('shopper::pages/discounts.customer_eligibility'))
                                ->options(resolve(DiscountEligibilityManager::class)->options())
                                ->descriptions(resolve(DiscountEligibilityManager::class)->descriptions())
                                ->columns(2)
                                ->columnSpanFull()
                                ->required()
                                ->live(),
                            ...resolve(DiscountEligibilityFieldRegistry::class)->fields(),
                            Radio::make('min_required')
                                ->label(__('shopper::pages/discounts.min_requirement'))
                                ->inline()
                                ->inlineLabel(false)
                                ->options(DiscountRequirement::options())
                                ->required()
                                ->live(),
                            TextInput::make('min_required_value')
                                ->hiddenLabel()
                                ->numeric()
                                ->minValue(1)
                                ->suffix(fn (Get $get): ?string => match ($get('min_required')) {
                                    DiscountRequirement::Price->value => $this->resolveZoneCurrency($get('zone_id')),
                                    default => null,
                                })
                                ->dehydrateStateUsing(function (Get $get, $state) {
                                    if ($get('min_required') !== DiscountRequirement::Price->value) {
                                        return $state;
                                    }

                                    $currency = $this->resolveZoneCurrency($get('zone_id'));

                                    return is_no_division_currency($currency) ? (string) (int) $state : (string) ((int) round((float) $state * 100));
                                })
                                ->required(fn (Get $get): bool => $get('min_required') !== DiscountRequirement::None->value)
                                ->visible(fn (Get $get): bool => filled($get('min_required')) && $get('min_required') !== DiscountRequirement::None->value),
                            Grid::make()
                                ->schema([
                                    DateTimePicker::make('start_at')
                                        ->label(__('shopper::pages/discounts.start_date'))
                                        ->required()
                                        ->minDate(now())
                                        ->native(false),
                                    DateTimePicker::make('end_at')
                                        ->label(__('shopper::pages/discounts.end_date'))
                                        ->afterOrEqual('start_at')
                                        ->native(false),
                                ]),
                            Toggle::make('usage_number')
                                ->label(__('shopper::pages/discounts.usage_label'))
                                ->helperText(__('shopper::pages/discounts.usage_label_description'))
                                ->live(),
                            TextInput::make('usage_limit')
                                ->label(__('shopper::pages/discounts.usage_value'))
                                ->placeholder('10')
                                ->integer()
                                ->numeric()
                                ->minValue(1)
                                ->required(fn (Get $get): bool => (bool) $get('usage_number'))
                                ->visible(fn (Get $get): bool => (bool) $get('usage_number')),
                            Toggle::make('usage_limit_per_user')
                                ->label(__('shopper::pages/discounts.limit_one_per_user')),
                            Section::make(__('shopper::pages/discounts.sections.combinations'))
                                ->compact()
                                ->collapsed()
                                ->description(__('shopper::pages/discounts.sections.combinations_description'))
                                ->schema([
                                    Select::make('exclusivity_class')
                                        ->label(__('shopper::pages/discounts.exclusivity_class'))
                                        ->helperText(__('shopper::pages/discounts.exclusivity_class_helptext'))
                                        ->native(false)
                                        ->options([
                                            ExclusivityClass::Order->value => __('shopper::pages/discounts.exclusivity_order'),
                                            ExclusivityClass::Product->value => __('shopper::pages/discounts.exclusivity_product'),
                                            ExclusivityClass::Shipping->value => __('shopper::pages/discounts.exclusivity_shipping'),
                                        ]),
                                    Toggle::make('combinable')
                                        ->label(__('shopper::pages/discounts.combinable'))
                                        ->helperText(__('shopper::pages/discounts.combinable_helptext')),
                                    TextInput::make('priority')
                                        ->label(__('shopper::pages/discounts.priority'))
                                        ->helperText(__('shopper::pages/discounts.priority_helptext'))
                                        ->integer()
                                        ->numeric()
                                        ->minValue(0),
                                ]),
                        ]),
                    StepColumn::make(__('shopper::pages/discounts.wizard.campaign'))
                        ->icon(Untitledui::Announcement)
                        ->schema([
                            TextEntry::make('campaign_hint')
                                ->hiddenLabel()
                                ->state(new HtmlString(Blade::render(<<<'BLADE'
                                    <p class="text-sm text-sh-fg-muted">
                                        {{ __('shopper::pages/discounts.campaign_description') }}
                                    </p>
                                BLADE))),
                            Select::make('campaign_id')
                                ->label(__('shopper::pages/discounts.campaign'))
                                ->helperText(__('shopper::pages/discounts.campaign_helptext'))
                                ->placeholder(__('shopper::pages/discounts.campaign_none'))
                                ->options(fn (): array => $this->campaignOptions())
                                ->native(false)
                                ->searchable(),
                        ]),
                ])
                    ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                        <x-filament::button type="submit" wire:loading.attr="disabled">
                            <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                            {{ __('shopper::forms.actions.save') }}
                        </x-filament::button>
                     BLADE)))
                    ->persistStepInQueryString(),
            ])
            ->statePath('data')
            ->model(Discount::class);
    }

    public function store(): void
    {
        $this->authorize('discounts.create');

        /** @var array<string, mixed> $data */
        $data = $this->form->getState();

        $applyTo = $data['apply_to'] ?? null;
        $eligibility = (string) ($data['eligibility'] ?? '');

        $registry = resolve(DiscountEligibilityFieldRegistry::class);
        $formKey = $registry->formKeyFor($eligibility);

        $products = resolve(Product::class)::query()
            ->scopes('publish')
            ->whereIn('id', array_map('intval', (array) data_get($this->data, 'products', [])))
            ->pluck('id')
            ->map(fn ($id): int => (int) $id)
            ->all();

        $eligibilityIds = $formKey !== null
            ? $registry->resolveIds($eligibility, array_map('intval', (array) data_get($this->data, $formKey, [])))
            : [];

        if ($applyTo === DiscountApplyTo::Products->value && $products === []) {
            Notification::make()
                ->title(__('shopper::pages/discounts.products_picker.required'))
                ->danger()
                ->send();

            return;
        }

        if ($formKey !== null && $eligibilityIds === []) {
            Notification::make()
                ->title(__('shopper::pages/discounts.eligibility_picker.required'))
                ->danger()
                ->send();

            return;
        }

        $discount = app()->call(SaveAndDispatchDiscountAction::class, [
            'values' => Arr::except($data, array_merge(['products', 'usage_number'], $registry->formKeys())),
            'productsIds' => $products,
            'eligibilityIds' => $eligibilityIds,
            'trigger' => $data['trigger'] ?? null,
            'campaignId' => isset($data['campaign_id']) ? (int) $data['campaign_id'] : null,
        ]);

        Notification::make()
            ->title(__('shopper::pages/discounts.save', ['code' => $discount->code ?? __('shopper::pages/discounts.method_automatic')]))
            ->success()
            ->send();

        $this->redirectRoute(
            name: 'shopper.discounts.edit',
            parameters: ['record' => $discount->id],
            navigate: true,
        );
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.add-promotion-form');
    }

    protected function resolveZoneCurrency(mixed $zoneId): string
    {
        if (! is_numeric($zoneId)) {
            return shopper_currency();
        }

        $id = (int) $zoneId;

        if ($this->cachedZoneId !== $id) {
            $this->cachedZoneId = $id;
            $this->cachedZone = Zone::query()->find($id);
        }

        if ($this->cachedZone === null) {
            return shopper_currency();
        }

        return $this->cachedZone->currency_code;
    }

    /**
     * @return array<int, string>
     */
    protected function campaignOptions(): array
    {
        return Cache::remember(
            'shopper.campaigns.options',
            now()->addMinutes(5),
            fn (): array => Campaign::query()->orderBy('name')->pluck('name', 'id')->all(),
        );
    }
}
