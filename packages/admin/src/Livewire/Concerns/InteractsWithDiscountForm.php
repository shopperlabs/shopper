<?php

declare(strict_types=1);

namespace Shopper\Livewire\Concerns;

use BackedEnum;
use DateTimeInterface;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\View as SchemaView;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Shopper\Actions\Store\SaveAndDispatchDiscountAction;
use Shopper\Components\Section;
use Shopper\Components\Separator;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Zone;

trait InteractsWithDiscountForm
{
    #[Locked]
    public Discount $discount;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public bool $showAllProducts = false;

    public bool $showAllCustomers = false;

    protected int $itemsInlineLimit = 12;

    protected ?Zone $cachedSelectedZone = null;

    protected ?int $cachedSelectedZoneId = null;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('shopper::pages/discounts.sections.general'))
                    ->aside()
                    ->compact()
                    ->description(__('shopper::pages/discounts.sections.general_description'))
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->schema([
                        Select::make('zone_id')
                            ->label(__('shopper::pages/settings/zones.single'))
                            ->relationship('zone', 'name')
                            ->native(false)
                            ->hint(__('shopper::forms.label.optional'))
                            ->disabled(fn (): bool => $this->isZoneFrozen())
                            ->helperText(fn (): ?string => $this->isZoneFrozen()
                                ? __('shopper::pages/discounts.zone_frozen_helper')
                                : null)
                            ->live(),
                        Radio::make('type')
                            ->label(__('shopper::forms.label.type'))
                            ->inline()
                            ->inlineLabel(false)
                            ->options(DiscountType::options())
                            ->required()
                            ->live(),
                        Grid::make()
                            ->schema([
                                TextInput::make('code')
                                    ->label(__('shopper::forms.label.code'))
                                    ->placeholder('CMR_SUMMER_900')
                                    ->helperText(__('shopper::pages/discounts.name_helptext'))
                                    ->hintAction(
                                        Action::make(__('shopper::words.generate'))
                                            ->color('info')
                                            ->action(function (Set $set): void {
                                                $set('code', mb_substr(mb_strtoupper(uniqid(Str::random(10))), 0, 10));
                                            }),
                                    )
                                    ->unique(table: Discount::class, column: 'code', ignoreRecord: true)
                                    ->live(onBlur: true)
                                    ->required(),
                                TextInput::make('value')
                                    ->label(
                                        fn (Get $get): ?string => match ($get('type')) {
                                            DiscountType::Percentage->value => __('shopper::pages/discounts.percentage'),
                                            DiscountType::FixedAmount->value => __('shopper::pages/discounts.fixed_amount'),
                                            default => null,
                                        }
                                    )
                                    ->suffix(
                                        fn (Get $get): ?string => match ($get('type')) {
                                            DiscountType::Percentage->value => '%',
                                            DiscountType::FixedAmount->value => $this->resolveZoneCurrency($get('zone_id')),
                                            default => null,
                                        }
                                    )
                                    ->afterStateHydrated(function (TextInput $component, $state): void {
                                        if ($this->discount->exists && $this->discount->type === DiscountType::FixedAmount && $state) {
                                            $currency = $this->discount->zone?->currency_code ?? shopper_currency(); // @phpstan-ignore nullsafe.neverNull
                                            $component->state(
                                                is_no_division_currency($currency) ? $state : $state / 100
                                            );
                                        }
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
                                    ->live(onBlur: true)
                                    ->required(),
                            ]),
                        Toggle::make('is_active')
                            ->label(__('shopper::forms.label.visibility'))
                            ->helperText(__('shopper::words.set_visibility', ['name' => __('shopper::pages/discounts.single')]))
                            ->live(),
                    ]),
                Separator::make(),
                Section::make(__('shopper::pages/discounts.sections.configuration'))
                    ->aside()
                    ->compact()
                    ->description(__('shopper::pages/discounts.sections.configuration_description'))
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->schema([
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
                            ->live(onBlur: true)
                            ->required(fn (Get $get): bool => (bool) $get('usage_number'))
                            ->visible(
                                fn (Get $get): bool => $get('usage_number') || $this->discount->usage_limit !== null
                            ),
                        Toggle::make('usage_limit_per_user')
                            ->label(__('shopper::pages/discounts.limit_one_per_user')),
                        Grid::make()
                            ->schema([
                                DateTimePicker::make('start_at')
                                    ->label(__('shopper::pages/discounts.start_date'))
                                    ->required()
                                    ->minDate(fn (): ?DateTimeInterface => $this->discount->exists ? null : now())
                                    ->native(false)
                                    ->live(onBlur: true),
                                DateTimePicker::make('end_at')
                                    ->label(__('shopper::pages/discounts.end_date'))
                                    ->afterOrEqual('start_at')
                                    ->native(false)
                                    ->live(onBlur: true),
                            ]),
                    ]),
                Separator::make(),
                Section::make(__('shopper::pages/discounts.sections.targeting'))
                    ->aside()
                    ->compact()
                    ->description(__('shopper::pages/discounts.sections.targeting_description'))
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->schema([
                        Radio::make('apply_to')
                            ->label(__('shopper::pages/discounts.applies_to'))
                            ->options(DiscountApplyTo::options())
                            ->inline()
                            ->required()
                            ->live(),
                        SchemaView::make('shopper::livewire.pages.discounts.partials.items-list')
                            ->viewData(['type' => 'products'])
                            ->visible(
                                fn (Get $get): bool => $get('apply_to') === DiscountApplyTo::Products->value
                            ),
                        Radio::make('eligibility')
                            ->label(__('shopper::pages/discounts.customer_eligibility'))
                            ->inline()
                            ->options(DiscountEligibility::options())
                            ->required()
                            ->live(),
                        SchemaView::make('shopper::livewire.pages.discounts.partials.items-list')
                            ->viewData(['type' => 'customers'])
                            ->visible(
                                fn (Get $get): bool => $get('eligibility') === DiscountEligibility::Customers->value
                            ),
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
                            ->live(onBlur: true)
                            ->suffix(
                                fn (Get $get): ?string => match ($get('min_required')) {
                                    DiscountRequirement::Price->value => $this->resolveZoneCurrency($get('zone_id')),
                                    default => null,
                                }
                            )
                            ->afterStateHydrated(function (TextInput $component, $state): void {
                                if ($this->discount->exists && $this->discount->min_required === DiscountRequirement::Price->value && $state) {
                                    $currency = $this->discount->zone?->currency_code ?? shopper_currency(); // @phpstan-ignore nullsafe.neverNull
                                    $component->state(
                                        is_no_division_currency($currency) ? $state : (string) ((int) $state / 100)
                                    );
                                }
                            })
                            ->dehydrateStateUsing(function (Get $get, $state) {
                                if ($get('min_required') !== DiscountRequirement::Price->value) {
                                    return $state;
                                }

                                $currency = $this->resolveZoneCurrency($get('zone_id'));

                                return is_no_division_currency($currency) ? (string) (int) $state : (string) ((int) round((float) $state * 100));
                            })
                            ->required(
                                fn (Get $get): bool => $get('min_required') !== DiscountRequirement::None->value
                            )
                            ->visible(function (Get $get): bool {
                                if ($get('min_required')) {
                                    return $get('min_required') !== DiscountRequirement::None->value;
                                }

                                return false;
                            }),
                    ]),
                Separator::make(),
                Section::make(__('shopper::pages/discounts.sections.advanced'))
                    ->aside()
                    ->compact()
                    ->collapsed()
                    ->description(__('shopper::pages/discounts.sections.advanced_description'))
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->schema([
                        KeyValue::make('metadata')->reorderable(),
                    ]),
            ])
            ->statePath('data')
            ->model($this->discount);
    }

    /**
     * @param  array<int>  $ids
     */
    #[On('discount.products.added')]
    public function addProductsToDiscount(array $ids): void
    {
        $current = (array) data_get($this->data, 'products', []);
        $merged = array_values(array_unique(array_merge(
            array_map('intval', $current),
            array_map('intval', $ids),
        )));

        data_set($this->data, 'products', $merged);
    }

    /**
     * @param  array<int>  $ids
     */
    #[On('discount.customers.added')]
    public function addCustomersToDiscount(array $ids): void
    {
        $current = (array) data_get($this->data, 'customers', []);
        $merged = array_values(array_unique(array_merge(
            array_map('intval', $current),
            array_map('intval', $ids),
        )));

        data_set($this->data, 'customers', $merged);
    }

    public function toggleShowAllProducts(): void
    {
        $this->showAllProducts = ! $this->showAllProducts;
    }

    public function toggleShowAllCustomers(): void
    {
        $this->showAllCustomers = ! $this->showAllCustomers;
    }

    public function getItemsInlineLimit(): int
    {
        return $this->itemsInlineLimit;
    }

    public function confirmClearProductsAction(): Action
    {
        return Action::make('confirmClearProducts')
            ->modalHeading(__('shopper::pages/discounts.apply_to_switch.heading'))
            ->modalDescription(__('shopper::pages/discounts.apply_to_switch.description'))
            ->modalSubmitActionLabel(__('shopper::pages/discounts.apply_to_switch.submit'))
            ->modalCancelActionLabel(__('shopper::pages/discounts.apply_to_switch.cancel'))
            ->modalWidth(Width::Medium)
            ->color('danger')
            ->action(function (array $arguments): void {
                $target = (string) ($arguments['target'] ?? DiscountApplyTo::Order->value);

                data_set($this->data, 'apply_to', $target);
                data_set($this->data, 'products', []);
            });
    }

    public function confirmClearCustomersAction(): Action
    {
        return Action::make('confirmClearCustomers')
            ->modalHeading(__('shopper::pages/discounts.eligibility_switch.heading'))
            ->modalDescription(__('shopper::pages/discounts.eligibility_switch.description'))
            ->modalSubmitActionLabel(__('shopper::pages/discounts.eligibility_switch.submit'))
            ->modalCancelActionLabel(__('shopper::pages/discounts.eligibility_switch.cancel'))
            ->modalWidth(Width::Medium)
            ->color('danger')
            ->action(function (array $arguments): void {
                $target = (string) ($arguments['target'] ?? DiscountEligibility::Everyone->value);

                data_set($this->data, 'eligibility', $target);
                data_set($this->data, 'customers', []);
            });
    }

    public function removeProductFromDiscount(int $productId): void
    {
        $current = (array) data_get($this->data, 'products', []);

        data_set(
            $this->data,
            'products',
            array_values(array_filter(
                array_map('intval', $current),
                fn (int $id): bool => $id !== $productId,
            )),
        );
    }

    public function removeCustomerFromDiscount(int $customerId): void
    {
        $current = (array) data_get($this->data, 'customers', []);

        data_set(
            $this->data,
            'customers',
            array_values(array_filter(
                array_map('intval', $current),
                fn (int $id): bool => $id !== $customerId,
            )),
        );
    }

    /**
     * @return EloquentCollection<int, \Illuminate\Database\Eloquent\Model>
     */
    #[Computed]
    public function selectedProducts(): EloquentCollection
    {
        $ids = array_values(array_filter(
            array_map('intval', (array) data_get($this->data, 'products', [])),
        ));

        if ($ids === []) {
            return new EloquentCollection;
        }

        return resolve(Product::class)::query()
            ->whereIn('id', $ids)
            ->get();
    }

    /**
     * @return EloquentCollection<int, \Illuminate\Database\Eloquent\Model>
     */
    #[Computed]
    public function selectedCustomers(): EloquentCollection
    {
        $ids = array_values(array_filter(
            array_map('intval', (array) data_get($this->data, 'customers', [])),
        ));

        if ($ids === []) {
            return new EloquentCollection;
        }

        return config('auth.providers.users.model')::query()
            ->whereIn('id', $ids)
            ->get();
    }

    public function save(): void
    {
        $this->authorize($this->discount->exists ? 'discounts.edit' : 'discounts.create');

        /** @var array<string, mixed> $data */
        $data = $this->form->getState();

        $applyTo = $data['apply_to'] ?? null;
        $eligibility = $data['eligibility'] ?? null;
        $products = (array) data_get($this->data, 'products', []);
        $customers = (array) data_get($this->data, 'customers', []);

        if ($applyTo === DiscountApplyTo::Products->value && $products === []) {
            Notification::make()
                ->title(__('shopper::pages/discounts.products_picker.required'))
                ->danger()
                ->send();

            return;
        }

        if ($eligibility === DiscountEligibility::Customers->value && $customers === []) {
            Notification::make()
                ->title(__('shopper::pages/discounts.customers_picker.required'))
                ->danger()
                ->send();

            return;
        }

        $discountFormValues = Arr::except($data, ['products', 'customers', 'usage_number']);

        $this->discount = app()->call(SaveAndDispatchDiscountAction::class, [
            'values' => $discountFormValues,
            'discountId' => $this->discount->id ?? null,
            'productsIds' => $products,
            'customersIds' => $customers,
        ]);

        Notification::make()
            ->title(__('shopper::pages/discounts.save', ['code' => $this->discount->code]))
            ->success()
            ->send();

        $this->redirectRoute(
            name: 'shopper.discounts.edit',
            parameters: ['record' => $this->discount->id],
            navigate: true,
        );
    }

    protected function isZoneFrozen(): bool
    {
        return $this->discount->exists
            && $this->discount->total_use > 0
            && $this->discount->type === DiscountType::FixedAmount;
    }

    protected function getSelectedZone(?int $zoneId): ?Zone
    {
        if ($zoneId === null) {
            return null;
        }

        if ($this->cachedSelectedZoneId === $zoneId && $this->cachedSelectedZone !== null) {
            return $this->cachedSelectedZone;
        }

        $this->cachedSelectedZoneId = $zoneId;
        $this->cachedSelectedZone = Zone::query()->find($zoneId);

        return $this->cachedSelectedZone;
    }

    protected function resolveZoneCurrency(mixed $zoneId): string
    {
        $zone = $this->getSelectedZone(is_numeric($zoneId) ? (int) $zoneId : null);

        if ($zone === null) {
            return shopper_currency();
        }

        return $zone->currency_code;
    }

    /**
     * @return array{
     *     code: ?string,
     *     type: ?DiscountType,
     *     type_label: ?string,
     *     currency: string,
     *     zone_name: ?string,
     *     apply_to: ?DiscountApplyTo,
     *     eligibility: ?DiscountEligibility,
     *     min_required: ?DiscountRequirement,
     *     minimum_label: ?string,
     *     usage_limit: ?int,
     *     start_at: ?string,
     *     end_at: ?string,
     *     is_active: bool,
     *     products_count: int,
     *     customers_count: int,
     * }
     */
    protected function buildSummary(): array
    {
        $state = $this->data ?? [];
        $type = $this->coerceEnum($state['type'] ?? null, DiscountType::class);
        $applyTo = $this->coerceEnum($state['apply_to'] ?? null, DiscountApplyTo::class);
        $eligibility = $this->coerceEnum($state['eligibility'] ?? null, DiscountEligibility::class);
        $minRequired = $this->coerceEnum($state['min_required'] ?? null, DiscountRequirement::class);
        $zoneId = isset($state['zone_id']) && is_numeric($state['zone_id']) ? (int) $state['zone_id'] : null;
        $zone = $this->getSelectedZone($zoneId);
        $currency = $zone?->currency_code ?? shopper_currency();
        $rawValue = isset($state['value']) && $state['value'] !== '' ? (float) $state['value'] : null;

        $typeLabel = null;

        if ($rawValue !== null && $type === DiscountType::Percentage) {
            $typeLabel = __('shopper::pages/discounts.summary.type_percentage', ['value' => (int) $rawValue]);
        } elseif ($rawValue !== null && $type === DiscountType::FixedAmount) {
            $cents = is_no_division_currency($currency) ? (int) $rawValue : (int) round($rawValue * 100);
            $typeLabel = __('shopper::pages/discounts.summary.type_fixed_amount', [
                'amount' => shopper_money_format($cents, $currency),
            ]);
        }

        $minimumLabel = null;
        $minRequiredValue = isset($state['min_required_value']) && $state['min_required_value'] !== ''
            ? (float) $state['min_required_value']
            : null;

        if ($minRequired === DiscountRequirement::Price && $minRequiredValue !== null) {
            $cents = is_no_division_currency($currency) ? (int) $minRequiredValue : (int) round($minRequiredValue * 100);
            $minimumLabel = __('shopper::pages/discounts.summary.minimum_price', [
                'amount' => shopper_money_format($cents, $currency),
            ]);
        } elseif ($minRequired === DiscountRequirement::Quantity && $minRequiredValue !== null) {
            $minimumLabel = __('shopper::pages/discounts.summary.minimum_quantity', [
                'count' => (int) $minRequiredValue,
            ]);
        }

        return [
            'code' => isset($state['code']) && $state['code'] !== '' ? (string) $state['code'] : null,
            'type' => $type,
            'type_label' => $typeLabel,
            'currency' => $currency,
            'zone_name' => $zone?->name,
            'apply_to' => $applyTo,
            'eligibility' => $eligibility,
            'min_required' => $minRequired,
            'minimum_label' => $minimumLabel,
            'usage_limit' => isset($state['usage_limit']) && $state['usage_limit'] !== '' ? (int) $state['usage_limit'] : null,
            'start_at' => isset($state['start_at']) && $state['start_at'] !== '' ? (string) $state['start_at'] : null,
            'end_at' => isset($state['end_at']) && $state['end_at'] !== '' ? (string) $state['end_at'] : null,
            'is_active' => (bool) ($state['is_active'] ?? false),
            'products_count' => is_array($state['products'] ?? null) ? count($state['products']) : 0,
            'customers_count' => is_array($state['customers'] ?? null) ? count($state['customers']) : 0,
        ];
    }

    /**
     * @template TEnum of \BackedEnum
     *
     * @param  class-string<TEnum>  $enum
     * @return TEnum|null
     */
    private function coerceEnum(mixed $value, string $enum): ?BackedEnum
    {
        if ($value instanceof $enum) {
            return $value;
        }

        if ($value === null || $value === '') {
            return null;
        }

        return $enum::tryFrom(is_string($value) ? $value : (string) $value);
    }
}
