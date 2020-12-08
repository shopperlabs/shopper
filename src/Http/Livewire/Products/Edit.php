<?php

namespace Shopper\Framework\Http\Livewire\Products;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;
use Livewire\Component;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class Edit extends Component
{
    /**
     * Product Listeners.
     *
     * @var string[]
     */
    protected $listeners = [
        'productHasUpdated'
    ];

    /**
     * Product Model.
     *
     * @var Model
     */
    public $product;

    /**
     * Component Mount method.
     *
     * @return void
     */
    public function mount($product)
    {
        $this->product = $product;
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

    /**
     *  Removed a product to the storage.
     *
     * @throws Exception
     */
    public function destroy()
    {
        (new ProductRepository())->getById($this->product->id)->delete();

        session()->flash('success', __("The product has been correctly removed."));
        $this->redirectRoute('shopper.products.index');
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        return view('shopper::livewire.products.edit');
    }
}
