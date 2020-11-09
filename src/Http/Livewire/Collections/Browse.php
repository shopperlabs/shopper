<?php

namespace Shopper\Framework\Http\Livewire\Collections;

use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;
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

    /**
     * Remove a record to the database.
     *
     * @param  int  $id
     * @throws \Exception
     */
    public function remove(int $id)
    {
        (new CollectionRepository())->getById($id)->delete();

        $this->dispatchBrowserEvent('item-removed');
        $this->dispatchBrowserEvent('notify', [
            'title' => __("Deleted"),
            'message' => __("The collection has successfully removed!")
        ]);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.collections.browse', [
            'total' => (new CollectionRepository())->count(),
            'collections' => (new CollectionRepository())
                ->where('name', '%'. $this->search .'%', 'like')
                ->orderBy($this->sortBy ?? 'name', $this->sortDirection)
                ->paginate(10)
        ]);
    }
}
