<?php

namespace Shopper\Framework\Http\Components\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;

class CategoryList extends Component
{
    use WithPagination;

    /**
     * @var CategoryRepository
     */
    protected $repository;

    public function mount(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function paginationView()
    {
        return 'shopper::components.wire-pagination-links';
    }

    public function hydrate()
    {
        $this->repository = new CategoryRepository();
    }

    public function render()
    {
        return view('shopper::components.categories.list', [
            'categories' => $this->repository->paginate(10),
        ]);
    }
}
