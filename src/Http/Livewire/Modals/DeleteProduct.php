<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class DeleteProduct extends ModalComponent
{
    public int $productId;
    public string $type;
    public ?string $route;

    public function mount(int $id, string $type = 'product', string $route = null)
    {
        $this->productId = $id;
        $this->type = $type;
        $this->route = $route;
    }

    public function delete()
    {
        if ($this->type === 'product') {
            (new ProductRepository())->getById($this->productId)->delete();
        } else {
            (new ProductRepository())->getById($this->productId)->forceDelete();
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
