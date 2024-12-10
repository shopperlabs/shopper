<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Computed;
use Shopper\Actions\Store\Product\SavePricingAction;
use Shopper\Core\Models\Currency;
use Shopper\Livewire\Components\SlideOverComponent;

/**
 * @property Form $form
 * @property Collection $currencies
 */
class ManagePricing extends SlideOverComponent implements HasForms
{
    use InteractsWithForms;

    public $model;

    public ?int $currencyId = null;

    public ?array $data = [];

    /**
     * @param  class-string | string  $modelType
     */
    public function mount(int $modelId, string $modelType, ?int $currencyId = null): void
    {
        $this->model = $modelType::with('prices')->find($modelId);
        $this->currencyId = $currencyId;

        $this->form->fill($this->getModelPrices());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                $this->currencies
                    ->map(fn (Currency $currency, $index): Forms\Components\Group => Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Placeholder::make($currency->code)
                                ->label("{$currency->name} ({$currency->symbol})"),
                            Forms\Components\Group::make()
                                ->schema([
                                    Forms\Components\TextInput::make('amount')  // @phpstan-ignore-line
                                        ->label(__('shopper::forms.label.price_amount'))
                                        ->helperText(__('shopper::pages/products.amount_price_help_text'))
                                        ->statePath($currency->id . '.amount')
                                        ->numeric()
                                        ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                        ->required(fn (Forms\Get $get) => $get($currency->id . '.compare_amount') !== null)
                                        ->suffix($currency->code)
                                        ->live()
                                        ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2),
                                    Forms\Components\TextInput::make('compare_amount')  // @phpstan-ignore-line
                                        ->label(__('shopper::forms.label.compare_price'))
                                        ->helperText(__('shopper::pages/products.compare_price_help_text'))
                                        ->statePath($currency->id . '.compare_amount')
                                        ->afterStateUpdated(
                                            fn (?string $state, Forms\Set $set) => $state ?? $set($currency->id . '.compare_amount', null)
                                        )
                                        ->numeric()
                                        ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                        ->suffix($currency->code)
                                        ->live()
                                        ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2),
                                    Forms\Components\TextInput::make('cost_amount')  // @phpstan-ignore-line
                                        ->label(__('shopper::forms.label.cost_per_item'))
                                        ->helperText(__('shopper::pages/products.cost_per_items_help_text'))
                                        ->statePath($currency->id . '.cost_amount')
                                        ->numeric()
                                        ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                        ->suffix($currency->code)
                                        ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2),
                                ])
                                ->columns(3),
                            Forms\Components\Placeholder::make('')
                                ->content(new HtmlString(
                                    "<div class='py-2'><div class='border-t border-gray-100 dark:border-white/10'></div></div>"
                                ))
                                ->visible($index + 1 !== count(shopper_setting('currencies'))),
                        ]))
                    ->toArray()
            )
            ->statePath('data')
            ->model($this->model);
    }

    protected function getModelPrices(): array
    {
        $prices = collect();

        foreach ($this->model->prices as $price) {
            $prices->put(
                $price->currency_id,
                [
                    'amount' => $price->amount,
                    'compare_amount' => $price->compare_amount === 0 ? null : $price->compare_amount,
                    'cost_amount' => $price->cost_amount === 0 ? null : $price->compare_amount,
                ]
            );
        }

        return $prices->toArray();
    }

    #[Computed]
    public function currencies(): Collection
    {
        return Currency::query()
            ->select('id', 'name', 'code', 'symbol')
            ->whereIn(
                column: 'id',
                values: $this->currencyId ? [$this->currencyId] : shopper_setting('currencies')
            )
            ->get();
    }

    public static function panelMaxWidth(): string
    {
        return '4xl';
    }

    public function save(): void
    {
        $this->validate();

        app()->call(SavePricingAction::class, [
            'model' => $this->model,
            'pricing' => $this->form->getState(),
        ]);

        Notification::make()
            ->title(__('shopper::pages/products.notifications.manage_pricing'))
            ->success()
            ->send();

        $this->dispatch('product.pricing.manage');

        $this->closePanel();
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.add-pricing');
    }
}
