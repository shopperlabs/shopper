<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Events\Products\ProductRemoved;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class DeleteProduct extends ModalComponent
{
    public int $productId;

    public string $type;

    public ?string $route = null;

    public function mount(int $id, string $type, string $route = null): void
    {
        $this->productId = $id;
        $this->type = $type;
        $this->route = $route;
    }

    public function delete(): void
    {
        $product = (new ProductRepository())->getById($this->productId);

        event(new ProductRemoved($product));

        if ($this->type === 'product') {
            $product->delete();
        } else {
            $product->forceDelete();
        }

        session()->flash('success', __('The :item has been correctly removed.', ['item' => $this->type]));

        if ($this->type === 'product') {
            $this->redirectRoute('shopper.products.index');
        } else {
            $this->redirect($this->route);
        }
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.delete-product');
    }
}
