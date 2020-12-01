<?php

namespace Shopper\Framework\Http\Livewire\Products;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Events\Products\ProductRemoved;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Traits\WithSorting;

class Browse extends Component
{
    use WithPagination, WithSorting;

    /**
     * Search.
     *
     * @var string
     */
    public $search;

    /**
     * Product status filter.
     *
     * @var string
     */
    public $is_visible;

    /**
     * Display More filters for products.
     *
     * @var bool
     */
    public $moreFilters = false;

    /**
     * Product brand id.
     *
     * @var int
     */
    public $brand_id;

    /**
     * Product category id.
     *
     * @var int
     */
    public $category_id;

    /**
     * Product collection id.
     *
     * @var int
     */
    public $collection_id;

    /**
     * Custom Livewire pagination view.
     *
     * @return string
     */
    public function paginationView()
    {
        return 'shopper::livewire.wire-pagination-links';
    }

    /**
     * Reset Filter on status.
     *
     * @return void
     */
    public function resetStatusFilter()
    {
        $this->is_visible = null;
    }

    /**
     * Remove a record to the database.
     *
     * @param  int  $id
     * @throws \Exception
     */
    public function remove(int $id)
    {
        $product = (new ProductRepository())->getById($id);

        event(new ProductRemoved($product));

        $product->delete();

        $this->dispatchBrowserEvent('item-removed');
        $this->notify([
            'title' => __("Deleted"),
            'message' => __("The product has successfully removed!")
        ]);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.products.browse', [
            'total' => (new ProductRepository())->count(),
            'brands' => (new BrandRepository())->makeModel()->scopes('enabled')->select('name', 'id')->get(),
            'categories' => (new CategoryRepository())->makeModel()->scopes('enabled')->select('name', 'id')->get(),
            'collections' => (new CollectionRepository())->get(['name', 'id']),
            'products' => (new ProductRepository())
                ->with('brand')
                ->makeModel()
                ->where(function (Builder $query) {
                    $query->where('name', 'like', '%'. $this->search .'%');
                    $query->where('parent_id', null);
                })
                ->where(function (Builder $query) {
                    if ($this->brand_id) {
                        $query->where('brand_id', $this->brand_id);
                    }

                    if ($this->collection_id) {
                        $query->whereHas('collections', function (Builder $q) {
                            $q->whereIn('id', [$this->collection_id]);
                        });
                    }

                    if ($this->category_id) {
                        $query->whereHas('categories', function (Builder $q) {
                            $q->whereIn('id', [$this->category_id]);
                        });
                    }
                })
                ->where(function (Builder $query) {
                    if ($this->is_visible !== null) {
                        $query->where('is_visible', boolval($this->is_visible));
                    }
                })
                ->orderBy($this->sortBy ?? 'name', $this->sortDirection)
                ->paginate(10),
        ]);
    }
}
