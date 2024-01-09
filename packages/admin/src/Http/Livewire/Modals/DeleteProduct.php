<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Shopper\Core\Events\Products\Deleted as ProductDeleted;
use Shopper\Core\Repositories\Store\ProductRepository;

class DeleteProduct extends ModalComponent
{
    public int $productId;

    public string $type;

    public ?string $route = null;

    public function mount(int $id, string $type, ?string $route = null): void
    {
        $this->productId = $id;
        $this->type = $type;
        $this->route = $route;
    }

    public function delete(): void
    {
        $product = (new ProductRepository())->getById($this->productId);

        event(new ProductDeleted($product));

        session()->flash('success', __('shopper::notifications.products.remove', ['item' => $this->type]));

        if ($this->type === 'product') {
            $product->delete();
            $this->redirectRoute('shopper.products.index');
        } else {
            $product->forceDelete();
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
