<?php

namespace Shopper\Framework\Http\Components\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class ProductList extends Component
{
    use WithPagination;

    /**
     * @var ProductRepository
     */
    protected $repository;

    public function mount(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function paginationView()
    {
        return 'shopper::components.livewire.wire-pagination-links';
    }

    public function hydrate()
    {
        $this->repository = new ProductRepository();
    }

    public function render()
    {
        return view('shopper::components.livewire.products.list', [
            'products' => $this->repository->orderBy('created_at', 'desc')->paginate(10),
        ]);
    }
}
