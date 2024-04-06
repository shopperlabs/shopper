<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Form;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Components;

class Shipping extends Component implements HasForms
{
    use InteractsWithForms;

    public $product;

    public ?array $data = [];

    public function mount($product): void
    {
        $this->product = $product;

        $this->form->fill($this->product->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Section::make(__('shopper::words.shipping'))
                    ->description(__('shopper::pages/products.shipping.description'))
                    ->aside()
                    ->compact()
                    ->schema([
                        Forms\Components\Checkbox::make('backorder')
                            ->label(__('shopper::pages/products.product_can_returned'))
                            ->helperText(__('shopper::pages/products.product_can_returned_help_text')),

                        Forms\Components\Checkbox::make('require_shipping')
                            ->label(__('shopper::pages/products.product_shipped'))
                            ->helperText(__('shopper::pages/products.product_shipped_help_text'))
                            ->default(true)
                            ->live(),
                    ]),

                Forms\Components\Group::make()
                    ->schema([
                        Components\Separator::make(),

                        Components\Section::make(__('shopper::pages/products.shipping.package_dimension'))
                            ->description(__('shopper::pages/products.shipping.package_dimension_description'))
                            ->aside()
                            ->compact()
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema(Components\Form\ShippingField::make()),
                            ]),
                    ])
                    ->visible(fn (Forms\Get $get): bool => $get('require_shipping')),
            ])
            ->statePath('data')
            ->model($this->product);
    }

    public function store(): void
    {
        $this->product->update($this->form->getState());

        $this->dispatch('productHasUpdated');

        Notification::make()
            ->body(__('shopper::pages/products.notifications.shipping_update'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.forms.shipping');
    }
}
