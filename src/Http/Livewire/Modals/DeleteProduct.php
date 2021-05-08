<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class DeleteProduct extends ModalComponent
{
    public int $productId;

    public function mount(int $id)
    {
        $this->productId = $id;
    }

    public function delete()
    {
        (new ProductRepository())->getById($this->productId)->delete();

        session()->flash('success', __("The :item has been correctly removed.", ['item' => 'product']));

        $this->redirectRoute('shopper.products.index');
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
