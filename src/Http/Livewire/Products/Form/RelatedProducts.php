<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Component;
use WireUi\Traits\Actions;

class RelatedProducts extends Component
{
    use Actions;

    public Model $product;
    public Collection $relatedProducts;

    protected $listeners = [
        'onProductsAddInRelated' => 'render',
    ];

    public function mount($product)
    {
        $this->product = $product;
        $this->relatedProducts = $product->relatedProducts;
    }

    public function remove(int $id)
    {
        $this->product->relatedProducts()->detach($id);
        $this->relatedProducts = $this->product->relatedProducts;

        $this->emitSelf('onProductsAddInRelated');

        $this->notification()->success(
            __('shopper::layout.status.delete'),
            __('shopper::pages/products.notifications.remove_related')
        );
    }

    public function getProductsIdsProperty(): array
    {
        return array_merge($this->product->relatedProducts->modelKeys(), [$this->product->id]);
    }

    public function render()
    {
        return view('shopper::livewire.products.forms.form-related');
    }
}
