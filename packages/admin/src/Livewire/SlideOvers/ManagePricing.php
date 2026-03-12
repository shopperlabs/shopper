<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Shopper\Actions\Store\Product\SavePricingAction;
use Shopper\Components\Form\CurrenciesField;
use Shopper\Core\Contracts\Priceable;
use Shopper\Core\Models\Currency;
use Shopper\Livewire\Components\SlideOverComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Schema $form
 * @property-read Collection<int, Currency> $currencies
 */
class ManagePricing extends SlideOverComponent implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    /** @var (Model&Priceable<Model>) */
    public Model&Priceable $model;

    #[Locked]
    public ?int $currencyId = null;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public static function panelMaxWidth(): string
    {
        return '4xl';
    }

    /**
     * @param  class-string|string  $modelType
     */
    public function mount(int $modelId, string $modelType, ?int $currencyId = null): void
    {
        $this->authorize('edit_products');

        $this->model = $modelType::with('prices')->find($modelId);
        $this->currencyId = $currencyId;

        $this->form->fill($this->getModelPrices());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components(CurrenciesField::make($this->currencies))
            ->statePath('data')
            ->model($this->model);
    }

    /**
     * @return Collection<int, Currency>
     */
    #[Computed]
    public function currencies(): Collection
    {
        /** @var Collection<int, Currency> $currencies */
        $currencies = Currency::query()
            ->select('id', 'name', 'code', 'symbol')
            ->whereIn(
                column: 'id',
                values: $this->currencyId ? [$this->currencyId] : shopper_setting('currencies')
            )
            ->get();

        return $currencies;
    }

    public function save(): void
    {
        $this->authorize('edit_products');

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

    /**
     * @return array<array-key, array<string, mixed>>
     */
    protected function getModelPrices(): array
    {
        $prices = collect();

        // @phpstan-ignore-next-line
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
}
