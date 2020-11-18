<?php

namespace Shopper\Framework\Http\Livewire\Products;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
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
     * Custom Livewire pagination view.
     *
     * @return string
     */
    public function paginationView()
    {
        return 'shopper::livewire.wire-pagination-links';
    }

    public function render()
    {
        return view('shopper::livewire.products.browse', [
            'total' => (new ProductRepository())->count(),
            'products' => (new ProductRepository())
                ->makeModel()
                ->where(function (Builder $query) {
                    $query->where('parent_id', null);
                })
                ->where('name', '%'. $this->search .'%', 'like')
                ->orderBy($this->sortBy ?? 'name', $this->sortDirection)
                ->paginate(10),
        ]);
    }
}
