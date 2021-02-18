<?php

namespace Shopper\Framework\Http\Livewire\Collections;

use Livewire\Component;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class Products extends Component
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $collection;

    /**
     * Search product input.
     *
     * @var string
     */
    public $search;

    /**
     * Order by property for products.
     *
     * @var string
     */
    public $sortBy = 'name';

    /**
     * Sort value select input.
     *
     * @var string
     */
    public $sortValue = 'name';

    /**
     * Sort for display products.
     *
     * @var string
     */
    public $direction = 'asc';

    /**
     * Products id.
     *
     * @var array
     */
    public $productsIds = [];

    /**
     * Selected products.
     *
     * @var array
     */
    public $selectedProducts = [];

    /**
     * Display/Hide products lists
     *
     * @var bool
     */
    public $browsingProductsModal = false;

    /**
     * Component mount instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $collection
     * @return void
     */
    public function mount($collection)
    {
        $this->collection = $collection;
        $this->productsIds = $collection->products->modelKeys();
    }

    /**
     * Open products modal.
     *
     * @return void
     */
    public function launchModal()
    {
        $this->browsingProductsModal = true;
    }

    /**
     * Close modal products.
     *
     * @return void
     */
    public function closeModal()
    {
        $this->browsingProductsModal = false;
    }

    /**
     * Add products to current collection.
     *
     * @return void
     */
    public function addProducts()
    {
        $this->collection->products()->sync($this->selectedProducts);

        $this->closeModal();
    }

    /**
     * Removed product to the current collection.
     *
     * @param  int  $id
     * @return void
     */
    public function removeProduct(int $id)
    {
        $this->collection->products()->detach([$id]);

        $this->notify([
            'title' => __('Product removed'),
            'message' => __('The product have been correctly remove to this collection.')
        ]);
    }

    /**
     * Order by filter.
     *
     * @param  string  $sortBy
     * @return void
     */
    public function updatedSortBy(string $sortBy)
    {
        switch ($sortBy) {
            case 'alpha_asc':
                $this->sortValue = 'name';
                $this->direction = 'asc';
                break;
            case 'alpha_desc':
                $this->sortValue = 'name';
                $this->direction = 'desc';
                break;
            case 'price_asc':
                $this->sortValue = 'price_amount';
                $this->direction = 'asc';
                break;
            case 'price_desc':
                $this->sortValue = 'price_amount';
                $this->direction = 'desc';
                break;
            case 'created_asc':
                $this->sortValue = 'created_at';
                $this->direction = 'asc';
                break;
            case 'created_desc':
                $this->sortValue = 'created_at';
                $this->direction = 'desc';
                break;
        }

        $this->sortBy = $sortBy;
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.collections.products', [
            'products' => (new ProductRepository())
                ->where('name', '%'. $this->search .'%', 'like')
                ->get(['name', 'price_amount', 'id'])
                ->except($this->productsIds),
            'collectionProducts' => $this->collection
                ->products()
                ->orderBy($this->sortValue, $this->direction)
                ->get()
        ]);
    }
}
