<?php

namespace Shopper\Framework\Http\Livewire\Categories;

use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Traits\WithSorting;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;

class Browse extends Component
{
    use WithPagination;
    use WithSorting;

    public string $search = '';

    protected $listeners = ['onCategoriesReordered' => 'render'];

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
        (new CategoryRepository())->getById($id)->delete();

        $this->dispatchBrowserEvent('item-removed');
        $this->notify([
            'title' => __('Deleted'),
            'message' => __('The category has successfully removed!'),
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.categories.browse', [
            'total' => (new CategoryRepository())->count(),
            'categories' => (new CategoryRepository())
                ->where('name', '%' . $this->search . '%', 'like')
                ->orderBy($this->sortBy ?? 'created_at', $this->sortDirection)
                ->paginate(10),
        ]);
    }
}
