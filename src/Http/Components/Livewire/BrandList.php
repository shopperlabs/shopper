<?php

namespace Shopper\Framework\Http\Components\Livewire;

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
        return 'shopper::components.livewire.wire-pagination-links';
    }

    public function hydrate()
    {
        $this->repository = new BrandRepository();
    }

    public function render()
    {
        return view('shopper::components.livewire.brands.list', [
            'brands' => $this->repository->orderBy('created_at', 'desc')->paginate(10),
        ]);
    }
}
