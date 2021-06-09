<?php


namespace Shopper\Framework\Http\Livewire\Modals;


use Illuminate\Support\Collection;
use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class ProductsLists extends ModalComponent
{
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
            ->where('name', '%'. $this->search .'%', 'like')
            ->get(['name', 'price_amount', 'id'])
            ->except($this->exceptProductIds);
    }

    public function addSelectedProducts()
    {
        $this->collection->products()->sync($this->selectedProducts);

        $this->emit('onProductsAddInCollection');

        $this->closeModal();
    }

    public function render()
    {
        return view('shopper::livewire.modals.products-lists');
    }
}
