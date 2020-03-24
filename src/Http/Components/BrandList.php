<?php

namespace Shopper\Framework\Http\Components;

use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;

class BrandList extends Component
{
    use WithPagination;

    /**
     * @var BrandRepository
     */
    protected $repository;

    public function mount(BrandRepository $repository)
    {
        $this->repository = $repository;
    }

    public function paginationView()
    {
        return 'shopper::components.wire-pagination-links';
    }

    public function hydrate()
    {
        $this->repository = new BrandRepository();
    }

    public function render()
    {
        return view('shopper::components.brands.list', [
            'brands' => $this->repository->paginate(10),
        ]);
    }
}
