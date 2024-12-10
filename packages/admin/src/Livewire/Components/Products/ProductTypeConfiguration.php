<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products;

use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Shopper\Traits\SaveSettings;

/**
 * @property Form $form
 */
class ProductTypeConfiguration extends Component implements HasForms
{
    use InteractsWithForms;
    use SaveSettings;

    #[Reactive]
    public ?string $defaultProductType = null;

    public bool $hasConfig = false;

    public function mount(): void
    {
        $this->form->fill([
            'hasConfig' => (bool) $this->defaultProductType,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('hasConfig')
                    ->disabled($this->defaultProductType === null)
                    ->label(__('shopper::pages/products.product_type'))
                    ->debounce()
                    ->live()
                    ->afterStateUpdated(function ($state): void {
                        $this->saveSettings(['default_product_type' => $state ? $this->defaultProductType : null]);

                        $this->dispatch('product-type.updated');
                    })
                    ->helperText(__('shopper::pages/products.product_type_helpText')),
            ]);
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.type-configuration');
    }
}
