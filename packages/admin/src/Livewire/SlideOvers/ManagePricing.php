<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Shopper\Actions\Store\Product\SavePricingAction;
use Shopper\Components\Form\CurrenciesField;
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

    #[Locked]
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
            ->schema(CurrenciesField::make($this->currencies))
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
