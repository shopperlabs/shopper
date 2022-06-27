<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use WireUi\Traits\Actions;

class ProductsLists extends ModalComponent
{
    use Actions;

    public $collection;
    public string $search = '';
    public array $exceptProductIds;
    public array $selectedProducts = [];

    public function mount(int $id, array $exceptProductIds = [])
    {
        $this->collection = (new CollectionRepository())->getById($id);
        $this->exceptProductIds = $exceptProductIds;
    }

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public function getProductsProperty()
    {
        return (new ProductRepository())
            ->where('name', '%' . $this->search . '%', 'like')
            ->get(['name', 'price_amount', 'id'])
            ->except($this->exceptProductIds);
    }

    public function addSelectedProducts()
    {
        $currentProducts = $this->collection->products->pluck('id')->toArray();
        $this->collection->products()->sync(array_merge($this->selectedProducts, $currentProducts));

        $this->emitUp('onProductsAddInCollection');

        $this->notification()->success(
            __('shopper::layout.status.added'),
            __('shopper::pages/collections.modal.success_message')
        );

        $this->closeModal();
    }

    public function render()
    {
        return view('shopper::livewire.modals.products-lists');
    }
}
