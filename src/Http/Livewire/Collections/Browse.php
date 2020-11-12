<?php

namespace Shopper\Framework\Http\Livewire\Collections;

use Illuminate\Database\Eloquent\Builder;
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
     * Collection type filter.
     *
     * @var string
     */
    public $type;

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
        $this->notify([
            'title' => __("Deleted"),
            'message' => __("The collection has successfully removed!")
        ]);
    }

    /**
     * Reset Filter on type.
     *
     * @return void
     */
    public function resetTypeFilter()
    {
        $this->type = null;
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
                ->makeModel()
                ->where('name', 'like', '%'. $this->search .'%')
                ->where(function (Builder $query) {
                    if ($this->type !== null) {
                        $query->where('type', $this->type);
                    }
                })
                ->orderBy($this->sortBy ?? 'name', $this->sortDirection)
                ->paginate(10)
        ]);
    }
}
