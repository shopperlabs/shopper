<?php

namespace Shopper\Framework\Http\Components\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;

class CollectionList extends Component
{
    use WithPagination;

    /**
     * @var CollectionRepository
     */
    protected $repository;

    public function mount(CollectionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function paginationView()
    {
        return 'shopper::components.livewire.wire-pagination-links';
    }

    public function hydrate()
    {
        $this->repository = new CollectionRepository();
    }

    public function render()
    {
        return view('shopper::components.livewire.collections.list', [
            'collections' => $this->repository->paginate(10),
        ]);
    }
}
