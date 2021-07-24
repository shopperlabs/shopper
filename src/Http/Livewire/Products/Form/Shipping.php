<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Http\Livewire\Products\WithAttributes;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class Shipping extends Component
{
    use WithAttributes;

    /**
     * Product Model.
     *
     * @var Model
     */
    public $product;

    /**
     * Product id.
     */
    public int $productId;

    /**
     * Component Mount method.
     */
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

    /**
     * Store/Update a entry to the storage.
     */
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

        $this->notify([
            'title' => __('Updated'),
            'message' => __('Product shipping successfully updated!'),
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.products.forms.form-shipping');
    }
}
