<?php

namespace Shopper\Framework\Http\Livewire\Products;

use Exception;
use Livewire\Component;
use Shopper\Framework\Repositories\InventoryRepository;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

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
     * Confirm action to delete product.
     *
     * @var bool
     */
    public $confirmDeleteProduct = false;

    /**
     * All locations available on the store.
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
     */
    public function mount($product)
    {
        $this->product = $product;
        $this->inventories = $inventories = (new InventoryRepository())->get();
        $this->inventory = $inventories->where('is_default', true)->first()->id;
    }

    /**
     * Launch modale to remove product.
     */
    public function openModale()
    {
        $this->confirmDeleteProduct = true;
    }

    /**
     * Close modale.
     */
    public function closeModale()
    {
        $this->confirmDeleteProduct = false;
    }

    /**
     * Product updated Listener.
     */
    public function productHasUpdated(int $id)
    {
        $this->product = (new ProductRepository())->getById($id);
    }

    /**
     * Removed a product to the storage.
     *
     * @throws Exception
     */
    public function destroy()
    {
        (new ProductRepository())->getById($this->product->id)->delete();

        session()->flash('success', __('The product has been correctly removed.'));
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
