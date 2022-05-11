<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Shopper\Framework\Http\Livewire\Products\WithAttributes;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use WireUi\Traits\Actions;

class Shipping extends Component
{
    use Actions, WithAttributes;

    public Model $product;
    public int $productId;

    public function mount($product)
    {
        $this->product = $product;
        $this->productId = $product->id;
        $this->requiresShipping = $product->requires_shipping;
        $this->backorder = $product->backorder;
        $this->weightValue = $product->weight_value;
        $this->weightUnit = $product->weight_unit;
        $this->heightValue = $product->height_value;
        $this->heightUnit = $product->height_unit;
        $this->widthValue = $product->width_value;
        $this->widthUnit = $product->width_unit;
        $this->volumeValue = $product->volume_value;
        $this->volumeUnit = $product->volume_unit;
    }

    public function store()
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

        $this->notification()->success(
            __('shopper::layout.status.updated'),
            __('shopper::pages/products.notifications.shipping_update')
        );
    }

    public function render()
    {
        return view('shopper::livewire.products.forms.form-shipping');
    }
}
