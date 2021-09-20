<?php

namespace Shopper\Framework\Http\Livewire\Collections;

use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;

class Browse extends Component
{
    use WithPagination;

    public string $search = '';

    public ?string $type = null;

    public function paginationView(): string
    {
        return 'shopper::livewire.wire-pagination-links';
    }

    /**
     * Remove a record to the database.
     *
     * @throws Exception
     */
    public function remove(int $id)
    {
        (new CollectionRepository())->getById($id)->delete();

        $this->dispatchBrowserEvent('item-removed');

        $this->notify([
            'title' => __('Deleted'),
            'message' => __('The collection has successfully removed!'),
        ]);
    }

    /**
     * Reset Filter on type.
     */
    public function resetTypeFilter()
    {
        $this->type = null;
    }

    public function render()
    {
        return view('shopper::livewire.collections.browse', [
            'total' => (new CollectionRepository())->count(),
            'collections' => (new CollectionRepository())
                ->makeModel()
                ->with('rules')
                ->where('name', 'like', '%' . $this->search . '%')
                ->where(function (Builder $query) {
                    if ($this->type !== null) {
                        $query->where('type', $this->type);
                    }
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10),
        ]);
    }
}
