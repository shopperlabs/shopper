<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Events\Products\ProductRemoved;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class DeleteProduct extends ModalComponent
{
    public int $productId;
    public string $type = 'product';
    public ?string $route = null;

    public function mount(int $id, string $type = 'product', ?string $route = null)
    {
        $this->productId = $id;
        $this->type = $type;
        $this->route = $route;
    }

    public function delete()
    {
        $product = (new ProductRepository())->getById($this->productId);

        event(new ProductRemoved($product));

        if ($this->type === 'product') {
            $product->delete();
        } else {
            $product->forceDelete();
        }

        session()->flash('success', __('The :item has been correctly removed.', ['item' => $this->type]));

        if ($this->route) {
            $this->redirectRoute('shopper.products.index');
        } else {
            $this->redirect($this->route);
        }
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function render()
    {
        return view('shopper::livewire.modals.delete-product');
    }
}
