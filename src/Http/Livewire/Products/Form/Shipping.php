<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Shopper\Framework\Http\Livewire\Products\WithAttributes;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class Shipping extends Component
{
    use WithAttributes;

    public Model $product;

    public int $productId;

    public function mount($product): void
    {
        $this->product = $product;
        $this->productId = $product->id;
        $this->requiresShipping = $product->requires_shipping;
        $this->backorder = $product->backorder;
        $this->weightValue = floatval($product->weight_value);
        $this->weightUnit = $product->weight_unit;
        $this->heightValue = floatval($product->height_value);
        $this->heightUnit = $product->height_unit;
        $this->widthValue = floatval($product->width_value);
        $this->widthUnit = $product->width_unit;
        $this->volumeValue = floatval($product->volume_value);
        $this->volumeUnit = $product->volume_unit;
    }

    public function store(): void
    {
        (new ProductRepository())->getById($this->productId)->update([
            'requires_shipping' => $this->requiresShipping,
            'backorder' => $this->backorder,
            'weight_value' => $this->weightValue ?? null,
            'weight_unit' => $this->weightUnit ?? null,
            'height_value' => $this->heightValue ?? null,
            'height_unit' => $this->heightUnit ?? null,
            'width_value' => $this->widthValue ?? null,
            'width_unit' => $this->widthUnit ?? null,
            'volume_value' => $this->volumeValue ?? null,
            'volume_unit' => $this->volumeUnit ?? null,
        ]);

        $this->emit('productHasUpdated', $this->productId);

        Notification::make()
            ->title(__('shopper::layout.status.updated'))
            ->body(__('shopper::pages/products.notifications.shipping_update'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.products.forms.form-shipping');
    }
}
