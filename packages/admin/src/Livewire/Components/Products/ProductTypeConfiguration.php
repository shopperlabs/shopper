<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Shopper\Core\Enum\ProductType;
use Shopper\Traits\HandlesAuthorizationExceptions;
use Shopper\Traits\SaveSettings;

/**
 * @property-read Schema $form
 */
class ProductTypeConfiguration extends Component implements HasActions, HasForms
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithForms;
    use SaveSettings;

    #[Reactive]
    public ?ProductType $defaultProductType = null;

    public bool $hasConfig = false;

    public function mount(): void
    {
        $this->form->fill([
            'hasConfig' => (bool) $this->defaultProductType?->value,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('hasConfig')
                    ->disabled($this->defaultProductType === null)
                    ->label(__('shopper::pages/products.product_type'))
                    ->debounce()
                    ->live()
                    ->afterStateUpdated(function (bool $state): void {
                        $this->saveSettings(['default_product_type' => $state ? $this->defaultProductType?->value : null]);

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
