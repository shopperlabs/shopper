<?php

namespace Shopper\Framework\Http\Livewire\Products;

use Livewire\Component;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Repositories\InventoryRepository;

class Edit extends Component
{
    /**
     * Product Listeners.
     *
     * @var string[]
     */
    protected $listeners = ['productHasUpdated'];

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
    public $inventory;

    /**
     * Component Mount method.
     *
     * @param  mixed  $product
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
     * @return void
     */
    public function productHasUpdated(int $id)
    {
        $this->product = (new ProductRepository())->getById($id);
    }

    public function destroy()
    {
        (new ProductRepository())->getById($this->product->id)->delete();

        session()->flash('success', __("The product has been correctly removed."));
        $this->redirectRoute('shopper.products.index');
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.products.edit', [
            'currency' => shopper_currency(),
        ]);
    }
}
