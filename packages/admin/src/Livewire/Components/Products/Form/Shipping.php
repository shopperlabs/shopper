<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Form;

use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Shopper\Components\Form\ShippingField;
use Shopper\Components\Section;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Schema $form
 */
class Shipping extends Component implements HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithSchemas;

    /** @var Model&Product */
    public Product $product;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->product->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('shopper::pages/products.shipping.package_dimension'))
                    ->aside()
                    ->compact()
                    ->extraAttributes(['class' => 'sh-section-aside'])
                    ->description(__('shopper::pages/products.shipping.package_dimension_description'))
                    ->schema([
                        Grid::make()->schema(ShippingField::make()),
                    ]),
            ])
            ->statePath('data')
            ->model($this->product);
    }

    public function store(): void
    {
        $this->product->update($this->form->getState());

        $this->dispatch('product.updated');

        Notification::make()
            ->title(__('shopper::pages/products.notifications.shipping_update'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.forms.shipping');
    }
}
