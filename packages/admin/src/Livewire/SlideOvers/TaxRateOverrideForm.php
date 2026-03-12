<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;
use Shopper\Components\Separator;
use Shopper\Contracts\SlideOverForm;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Contracts\Category;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\TaxRate;
use Shopper\Core\Models\TaxRateRule;
use Shopper\Core\Models\TaxZone;
use Shopper\Livewire\Components\SlideOverComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;
use Shopper\Traits\InteractsWithSlideOverForm;

/**
 * @property-read Schema $form
 */
class TaxRateOverrideForm extends SlideOverComponent implements HasActions, HasSchemas, SlideOverForm
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithSlideOverForm;

    public ?TaxRate $taxRate = null;

    public ?int $taxZoneId = null;

    public string $action = 'store';

    public ?string $title = null;

    public ?string $description = null;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public static function panelMaxWidth(): string
    {
        return '2xl';
    }

    public function mount(int $taxZoneId, ?int $taxRateId = null): void
    {
        $this->authorize('access_setting');

        $this->taxZoneId = $taxZoneId;

        $this->taxRate = $taxRateId
            ? TaxRate::with('rules')->find($taxRateId)
            : new TaxRate;

        $zone = TaxZone::with('country')->find($taxZoneId);

        $this->title = $taxRateId
            ? __('shopper::pages/settings/taxes.overrides.update', ['name' => $this->taxRate->name])
            : __('shopper::pages/settings/taxes.overrides.add_heading', ['name' => $zone->display_name]);

        $this->description = __('shopper::pages/settings/taxes.overrides.description');

        $targets = $this->taxRate->id
            ? $this->taxRate->rules->map(fn (TaxRateRule $rule): array => [
                'reference_type' => $rule->reference_type,
                'reference_id' => $rule->reference_id,
            ])->all()
            : [];

        $this->form->fill(array_merge(
            $this->taxRate->toArray(),
            ['targets' => $targets],
        ));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('shopper::forms.label.name'))
                            ->placeholder('Reduced VAT, Zero-rated...')
                            ->required(),
                        TextInput::make('rate')
                            ->label(__('shopper::pages/settings/taxes.rates.rate'))
                            ->numeric()
                            ->suffix('%')
                            ->required()
                            ->rules(['regex:/^\d{1,3}(\.\d{0,4})?$/']),
                    ])
                    ->columns(),
                TextInput::make('code')
                    ->label(__('shopper::forms.label.code'))
                    ->placeholder('VAT5, ZERO...'),
                Toggle::make('is_combinable')
                    ->label(__('shopper::pages/settings/taxes.rates.combinable'))
                    ->helperText(__('shopper::pages/settings/taxes.rates.combinable_help')),
                Separator::make(),
                Repeater::make('targets')
                    ->label(__('shopper::pages/settings/taxes.overrides.targets'))
                    ->helperText(__('shopper::pages/settings/taxes.overrides.targets_help'))
                    ->schema([
                        Select::make('reference_type')
                            ->label(__('shopper::pages/settings/taxes.overrides.target_type'))
                            ->options([
                                'product_type' => __('shopper::pages/settings/taxes.overrides.product_types'),
                                'product' => __('shopper::pages/settings/taxes.overrides.products'),
                                'category' => __('shopper::pages/settings/taxes.overrides.categories'),
                            ])
                            ->required()
                            ->live()
                            ->native(false),
                        Select::make('reference_id')
                            ->label(__('shopper::pages/settings/taxes.overrides.target_value'))
                            ->options(function (Get $get): array {
                                $type = $get('reference_type');
                                $siblings = collect($get('../../') ?? []);
                                $currentId = $get('reference_id');

                                $usedIds = $siblings
                                    ->where('reference_type', $type)
                                    ->pluck('reference_id')
                                    ->reject(fn ($id): bool => $id === $currentId)
                                    ->filter()
                                    ->all();

                                return match ($type) {
                                    'product_type' => collect(ProductType::cases())
                                        ->reject(fn (ProductType $pt): bool => $pt === ProductType::Variant)
                                        ->reject(fn (ProductType $pt): bool => in_array($pt->value, $usedIds, true))
                                        ->mapWithKeys(fn (ProductType $pt): array => [$pt->value => $pt->getLabel()])
                                        ->all(),
                                    'product' => resolve(Product::class)::query()
                                        ->when($usedIds, fn ($q) => $q->whereNotIn('id', $usedIds))
                                        ->limit(50)
                                        ->pluck('name', 'id')
                                        ->all(),
                                    'category' => resolve(Category::class)::query()
                                        ->when($usedIds, fn ($q) => $q->whereNotIn('id', $usedIds))
                                        ->limit(50)
                                        ->pluck('name', 'id')
                                        ->all(),
                                    default => [],
                                };
                            })
                            ->searchable()
                            ->required()
                            ->native(false),
                    ])
                    ->columns()
                    ->addActionLabel(__('shopper::pages/settings/taxes.overrides.add_target'))
                    ->defaultItems(0),
                Separator::make(),
                KeyValue::make('metadata')
                    ->label('Metadata')
                    ->reorderable(),
            ])
            ->statePath('data')
            ->model($this->taxRate);
    }

    public function store(): void
    {
        $this->authorize('access_setting');

        $data = $this->form->getState();
        $targets = collect(Arr::pull($data, 'targets', []))
            ->unique(fn (array $tx): string => $tx['reference_type'].':'.$tx['reference_id'])
            ->values()
            ->all();

        $data['tax_zone_id'] = $this->taxZoneId;
        $data['is_default'] = false;

        if ($this->taxRate->id) {
            $this->taxRate->update($data);
            $this->taxRate->rules()->delete();
        } else {
            $this->taxRate = TaxRate::query()->create($data);
        }

        foreach ($targets as $target) {
            TaxRateRule::query()->create([
                'tax_rate_id' => $this->taxRate->id,
                'reference_type' => $target['reference_type'],
                'reference_id' => (string) $target['reference_id'],
            ]);
        }

        Notification::make()
            ->title(__('shopper::notifications.save', ['item' => $this->taxRate->name]))
            ->success()
            ->send();

        $this->redirectRoute('shopper.settings.taxes', ['zone' => $this->taxZoneId]);
    }
}
