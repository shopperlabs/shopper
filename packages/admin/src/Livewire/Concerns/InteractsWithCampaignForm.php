<?php

declare(strict_types=1);

namespace Shopper\Livewire\Concerns;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;
use Livewire\Attributes\Locked;
use Shopper\Components\Section;
use Shopper\Components\Separator;
use Shopper\Core\Enum\CampaignBudgetType;
use Shopper\Core\Models\Campaign;
use Shopper\Core\Models\Currency;

trait InteractsWithCampaignForm
{
    #[Locked]
    public Campaign $campaign;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('shopper::pages/campaigns.sections.general'))
                    ->aside()
                    ->compact()
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->description(__('shopper::pages/campaigns.sections.general_description'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('shopper::pages/campaigns.name'))
                            ->placeholder(__('shopper::pages/campaigns.name_placeholder'))
                            ->required()
                            ->live(onBlur: true),
                        Select::make('currency_code')
                            ->label(__('shopper::pages/campaigns.currency'))
                            ->options(fn (): array => $this->currencyOptions())
                            ->native(false)
                            ->required()
                            ->disabled(fn (): bool => $this->isCurrencyFrozen())
                            ->helperText(fn (): ?string => $this->isCurrencyFrozen()
                                ? __('shopper::pages/campaigns.currency_frozen_helper')
                                : null)
                            ->live(),
                        Toggle::make('is_active')
                            ->label(__('shopper::forms.label.visibility'))
                            ->helperText(__('shopper::words.set_visibility', ['name' => __('shopper::pages/campaigns.single')]))
                            ->live(),
                    ]),
                Separator::make(),
                Section::make(__('shopper::pages/campaigns.sections.budget'))
                    ->aside()
                    ->compact()
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->description(__('shopper::pages/campaigns.sections.budget_description'))
                    ->schema([
                        Radio::make('budget_type')
                            ->label(__('shopper::pages/campaigns.budget_type'))
                            ->options(CampaignBudgetType::options())
                            ->descriptions($this->budgetTypeDescriptions())
                            ->required()
                            ->live(),
                        TextInput::make('budget_amount')
                            ->label(__('shopper::pages/campaigns.budget_amount'))
                            ->suffix(fn (Get $get): string => $this->resolveCurrency($get('currency_code')))
                            ->numeric()
                            ->minValue(0.01)
                            ->afterStateHydrated(function (TextInput $component, $state): void {
                                if ($this->campaign->exists && $state !== null && $state !== '') {
                                    $currency = $this->campaign->currency_code;
                                    $component->state(
                                        is_no_division_currency($currency) ? $state : $state / 100
                                    );
                                }
                            })
                            ->dehydrateStateUsing(function (Get $get, $state) {
                                if ($state === null || $state === '') {
                                    return null;
                                }

                                $currency = $this->resolveCurrency($get('currency_code'));

                                return is_no_division_currency($currency) ? (int) $state : (int) round((float) $state * 100);
                            })
                            ->required(fn (Get $get): bool => $this->budgetTypeHasSpendCap($get('budget_type')))
                            ->visible(fn (Get $get): bool => $this->budgetTypeHasSpendCap($get('budget_type')))
                            ->live(onBlur: true),
                        TextInput::make('budget_count')
                            ->label(__('shopper::pages/campaigns.budget_count'))
                            ->integer()
                            ->numeric()
                            ->minValue(1)
                            ->required(fn (Get $get): bool => $this->budgetTypeHasCountCap($get('budget_type')))
                            ->visible(fn (Get $get): bool => $this->budgetTypeHasCountCap($get('budget_type')))
                            ->live(onBlur: true),
                    ]),
                Separator::make(),
                Section::make(__('shopper::pages/campaigns.sections.schedule'))
                    ->aside()
                    ->compact()
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->description(__('shopper::pages/campaigns.sections.schedule_description'))
                    ->schema([
                        Grid::make()
                            ->schema([
                                DateTimePicker::make('starts_at')
                                    ->label(__('shopper::pages/discounts.start_date'))
                                    ->required()
                                    ->native(false)
                                    ->live(onBlur: true),
                                DateTimePicker::make('ends_at')
                                    ->label(__('shopper::pages/discounts.end_date'))
                                    ->afterOrEqual('starts_at')
                                    ->native(false)
                                    ->live(onBlur: true),
                            ]),
                    ]),
                Separator::make(),
                Section::make(__('shopper::pages/campaigns.sections.advanced'))
                    ->aside()
                    ->compact()
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->collapsed()
                    ->description(__('shopper::pages/campaigns.sections.advanced_description'))
                    ->schema([
                        KeyValue::make('metadata')->reorderable(),
                    ]),
            ])
            ->statePath('data')
            ->model($this->campaign);
    }

    public function save(): void
    {
        $this->authorize($this->campaign->exists ? 'campaigns.edit' : 'campaigns.create');

        /** @var array<string, mixed> $data */
        $data = $this->form->getState();

        // The spend/usage counters are ledger-owned; never let the form write them.
        $data = Arr::except($data, ['spent_amount', 'used_count', 'public_id']);

        if ($this->isCurrencyFrozen()) {
            $data['currency_code'] = $this->campaign->currency_code;
        }

        $budgetType = CampaignBudgetType::tryFrom((string) ($data['budget_type'] ?? CampaignBudgetType::None->value))
            ?? CampaignBudgetType::None;

        if (! $budgetType->hasSpendCap()) {
            $data['budget_amount'] = null;
        }

        if (! $budgetType->hasCountCap()) {
            $data['budget_count'] = null;
        }

        if ($this->campaign->exists) {
            $this->campaign->update($data);
        } else {
            $this->campaign = Campaign::query()->create($data);
        }

        Notification::make()
            ->title(__('shopper::pages/campaigns.save', ['name' => $this->campaign->name]))
            ->success()
            ->send();

        $this->redirectRoute(
            name: 'shopper.campaigns.edit',
            parameters: ['record' => $this->campaign->id],
            navigate: true,
        );
    }

    protected function isCurrencyFrozen(): bool
    {
        return $this->campaign->exists
            && ($this->campaign->spent_amount > 0 || $this->campaign->used_count > 0);
    }

    /**
     * @return array<string, string>
     */
    protected function currencyOptions(): array
    {
        return Currency::query()
            ->whereIn('id', shopper_setting('currencies') ?? [])
            ->orderBy('name')
            ->pluck('name', 'code')
            ->all();
    }

    protected function resolveCurrency(mixed $code): string
    {
        return is_string($code) && $code !== '' ? $code : shopper_currency();
    }

    protected function budgetTypeHasSpendCap(mixed $value): bool
    {
        return CampaignBudgetType::tryFrom((string) $value)?->hasSpendCap() ?? false;
    }

    protected function budgetTypeHasCountCap(mixed $value): bool
    {
        return CampaignBudgetType::tryFrom((string) $value)?->hasCountCap() ?? false;
    }

    /**
     * @return array<string, string>
     */
    protected function budgetTypeDescriptions(): array
    {
        $descriptions = [];

        foreach (CampaignBudgetType::cases() as $case) {
            $descriptions[$case->value] = $case->getDescription();
        }

        return $descriptions;
    }

    /**
     * @return array{
     *     name: ?string,
     *     currency: string,
     *     budget_type: ?CampaignBudgetType,
     *     budget_label: ?string,
     *     starts_at: ?string,
     *     ends_at: ?string,
     *     is_active: bool,
     * }
     */
    protected function buildSummary(): array
    {
        $state = $this->data ?? [];
        $budgetType = CampaignBudgetType::tryFrom((string) ($state['budget_type'] ?? ''));
        $currency = $this->isCurrencyFrozen()
            ? $this->campaign->currency_code
            : $this->resolveCurrency($state['currency_code'] ?? null);

        $budgetLabel = null;
        $parts = [];

        if ($budgetType?->hasSpendCap() && isset($state['budget_amount']) && $state['budget_amount'] !== '') {
            $amount = (float) $state['budget_amount'];
            $cents = is_no_division_currency($currency) ? (int) $amount : (int) round($amount * 100);
            $parts[] = shopper_money_format($cents, $currency);
        }

        if ($budgetType?->hasCountCap() && isset($state['budget_count']) && $state['budget_count'] !== '') {
            $parts[] = trans_choice('shopper::pages/discounts.summary.rows.usage_value', (int) $state['budget_count'], ['count' => (int) $state['budget_count']]);
        }

        if ($parts !== []) {
            $budgetLabel = implode(' · ', $parts);
        } elseif ($budgetType === CampaignBudgetType::None) {
            $budgetLabel = __('shopper::pages/campaigns.no_budget');
        }

        return [
            'name' => isset($state['name']) && $state['name'] !== '' ? (string) $state['name'] : null,
            'currency' => $currency,
            'budget_type' => $budgetType,
            'budget_label' => $budgetLabel,
            'starts_at' => isset($state['starts_at']) && $state['starts_at'] !== '' ? (string) $state['starts_at'] : null,
            'ends_at' => isset($state['ends_at']) && $state['ends_at'] !== '' ? (string) $state['ends_at'] : null,
            'is_active' => (bool) ($state['is_active'] ?? false),
        ];
    }
}
