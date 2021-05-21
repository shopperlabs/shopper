<?php

namespace Shopper\Framework\Http\Livewire\Categories;

use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;
use Shopper\Framework\Traits\WithSorting;

class Browse extends Component
{
    use WithPagination, WithSorting;

    protected $listeners = ['onCategoriesReordered' => 'render'];

    public string $search = '';

    public function paginationView(): string
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
        (new CategoryRepository())->getById($id)->delete();

        $this->dispatchBrowserEvent('item-removed');
        $this->notify([
            'title' => __("Deleted"),
            'message' => __("The category has successfully removed!")
        ]);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.categories.browse', [
            'total' => (new CategoryRepository())->count(),
            'categories' => (new CategoryRepository())
                ->where('name', '%'. $this->search .'%', 'like')
                ->orderBy($this->sortBy ?? 'name', $this->sortDirection)
                ->paginate(10)
        ]);
    }
}
