<?php

namespace Shopper\Framework\Http\Livewire\Products;

use Livewire\Component;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Repositories\InventoryRepository;

class Edit extends Component
{
    /**
     * Product Model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $product;

    /**
     * All locations available on the store.
     *
     * @var mixed
     */
    public $inventories;

    /**
     * Default inventory id.
     *
     * @var int
     */
    public int $inventory;

    protected $listeners = ['productHasUpdated'];

    /**
     * Component Mount method.
     *
     * @param  mixed  $product
     *
     * @return void
     */
    public function mount($product)
    {
        $this->product = $product;
        $this->inventories = $inventories = (new InventoryRepository())->get();
        $this->inventory = $inventories->where('is_default', true)->first()->id;
    }

    /**
     * Product updated Listener.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function productHasUpdated(int $id)
    {
        $this->product = (new ProductRepository())->getById($id);
    }

    public function render()
    {
        return view('shopper::livewire.products.edit', [
            'currency' => shopper_currency(),
        ]);
    }
}
