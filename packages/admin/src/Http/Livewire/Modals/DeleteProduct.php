<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Shopper\Core\Events\Products\Deleted as ProductDeleted;
use Shopper\Core\Repositories\Ecommerce\ProductRepository;

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

        event(new ProductDeleted($product));

        if ('product' === $this->type) {
            $product->delete();
        } else {
            $product->forceDelete();
        }

        session()->flash('success', __('The :item has been correctly removed.', ['item' => $this->type]));

        if ('product' === $this->type) {
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
