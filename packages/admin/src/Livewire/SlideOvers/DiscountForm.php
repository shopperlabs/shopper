<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Laravelcm\LivewireSlideOvers\SlideOverComponent;
use Shopper\Actions\Store\SaveAndDispatchDiscountAction;
use Shopper\Components\Separator;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountCondition;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Zone;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Schema $form
 */
class DiscountForm extends SlideOverComponent implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Discount $discount;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public static function panelMaxWidth(): string
    {
        return '3xl';
    }

    public function mount(?int $discountId = null): void
    {
        abort_unless($this->authorize('add_discounts') || $this->authorize('edit_discounts'), 403);

        $this->discount = $discountId
            ? Discount::query()->find($discountId)
            : new Discount;

        $products = collect();
        $customers = collect();

        if ($discountId) {
            if ($this->discount->items()->where('condition', DiscountCondition::Eligibility)->exists()) {
                $customerConditions = $this->discount->items()
                    ->with('discountable')
                    ->where('condition', DiscountCondition::Eligibility)
                    ->get();

                foreach ($customerConditions as $customerCondition) {
                    $customers->push($customerCondition->discountable);
                }
            }

            if ($this->discount->items()->where('condition', DiscountCondition::ApplyTo)->exists()) {
                $productConditions = $this->discount->items()
                    ->with('discountable')
                    ->where('condition', DiscountCondition::ApplyTo)
                    ->get();

                foreach ($productConditions as $productCondition) {
                    $products->push($productCondition->discountable);
                }
            }
        }

        $this->form->fill(array_merge(
            $this->discount->toArray(),
            ['usage_number' => $this->discount->usage_limit !== null],
            ['customers' => $customers->pluck('id')->all()],
            ['products' => $products->pluck('id')->all()],
        ));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        TextEntry::make('general')
                            ->label(__('shopper::words.general')),
                        Select::make('zone_id')
                            ->label(__('shopper::pages/settings/zones.single'))
                            ->relationship('zone', 'name')
                            ->native(false)
                            ->hint(__('shopper::forms.label.optional'))
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
                                    ->required(),
                                TextInput::make('value')
                                    ->label(
                                        fn (Get $get): ?string => match ($get('type')) {
                                            DiscountType::Percentage->value => __('shopper::pages/discounts.percentage'),
                                            DiscountType::FixedAmount->value => __('shopper::pages/discounts.fixed_amount'),
                                            default => null
                                        }
                                    )
                                    ->suffix(
                                        fn (Get $get): ?string => match ($get('type')) {
                                            DiscountType::Percentage->value => '%',
                                            DiscountType::FixedAmount->value => $get('zone_id')
                                                ? Zone::query()->find($get('zone_id'))->currency_code
                                                : shopper_currency(),
                                            default => null
                                        }
                                    )
                                    ->numeric()
                                    ->required(),
                            ]),
                        Toggle::make('is_active')
                            ->label(__('shopper::forms.label.visibility'))
                            ->helperText(__('shopper::words.set_visibility', ['name' => __('shopper::pages/discounts.single')])),
                    ]),
                Separator::make(),
                Group::make()
                    ->schema([
                        TextEntry::make('configuration')
                            ->label(__('shopper::words.configuration'))
                            ->state(new HtmlString(Blade::render(<<<'Blade'
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('shopper::pages/discounts.configuration_description') }}
                                </p>
                            Blade))),
                        Toggle::make('usage_number')
                            ->label(__('shopper::pages/discounts.usage_label'))
                            ->helperText(__('shopper::pages/discounts.usage_label_description'))
                            ->live(),
                        TextInput::make('usage_limit')
                            ->label(__('shopper::pages/discounts.usage_value'))
                            ->placeholder('10')
                            ->integer()
                            ->numeric()
                            ->required(fn (Get $get): bool => $get('usage_number'))
                            ->visible(
                                fn (Get $get): bool => $get('usage_number') || $this->discount->usage_limit !== null
                            ),
                        Toggle::make('usage_limit_per_user')
                            ->label(__('shopper::pages/discounts.limit_one_per_user')),
                        TextEntry::make('dates')
                            ->label(__('shopper::pages/discounts.active_dates'))
                            ->state(new HtmlString(Blade::render(<<<'Blade'
                                <p class="text-sm leading-6 text-gray-500 dark:text-gray-400">
                                    {{ __('shopper::pages/discounts.active_dates_description') }}
                                </p>
                            Blade))),
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
                    ]),
                Separator::make(),
                Group::make()
                    ->schema([
                        TextEntry::make('conditions')
                            ->label(__('shopper::words.conditions'))
                            ->state(new HtmlString(Blade::render(<<<'Blade'
                                <p class="text-sm leading-6 text-gray-500 dark:text-gray-400">
                                    {{ __('shopper::pages/discounts.condition_description') }}
                                </p>
                            Blade))),
                        Radio::make('apply_to')
                            ->label(__('shopper::pages/discounts.applies_to'))
                            ->options(DiscountApplyTo::options())
                            ->inline()
                            ->required()
                            ->live(),
                        Select::make('products')
                            ->label(__('shopper::pages/discounts.select_products'))
                            ->options(
                                resolve(Product::class)::query()
                                    ->scopes('publish')
                                    ->get()
                                    ->pluck('name', 'id')
                            )
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->optionsLimit(10)
                            ->minItems(1)
                            ->required(
                                fn (Get $get): bool => $get('apply_to') === DiscountApplyTo::Products->value
                            )
                            ->visible(
                                fn (Get $get): bool => $get('apply_to') === DiscountApplyTo::Products->value
                            ),
                        Radio::make('eligibility')
                            ->label(__('shopper::pages/discounts.customer_eligibility'))
                            ->inline()
                            ->options(DiscountEligibility::options())
                            ->required()
                            ->live(),
                        Select::make('customers')
                            ->label(__('shopper::pages/discounts.select_customers'))
                            ->options(
                                config('auth.providers.users.model')::query()
                                    ->scopes('customers')
                                    ->get()
                                    ->pluck('full_name', 'id')
                            )
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->optionsLimit(10)
                            ->minItems(1)
                            ->required(
                                fn (Get $get): bool => $get('eligibility') === DiscountEligibility::Customers->value
                            )
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
                            ->suffix(
                                fn (Get $get): ?string => match ($get('min_required')) {
                                    DiscountRequirement::Price->value => $get('zone_id')
                                        ? Zone::query()->find($get('zone_id'))->currency_code
                                        : shopper_currency(),
                                    default => null
                                }
                            )
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
                Group::make()
                    ->schema([
                        KeyValue::make('metadata')->reorderable(),
                    ]),
            ])
            ->statePath('data')
            ->model($this->discount);
    }

    public function store(): void
    {
        $data = $this->form->getState();
        $discountFormValues = Arr::except($data, ['products', 'customers', 'usage_number']);

        $this->discount = app()->call(SaveAndDispatchDiscountAction::class, [
            'values' => $discountFormValues,
            'discountId' => $this->discount->id ?? null,
            'productsIds' => data_get($data, 'products', []),
            'customersIds' => data_get($data, 'customers', []),
        ]);

        Notification::make()
            ->title(__('shopper::pages/discounts.save', ['code' => $this->discount->code]))
            ->success()
            ->send();

        $this->redirectRoute(
            name: 'shopper.discounts.index',
            navigate: true,
        );
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.discount-form');
    }
}
