<?php

namespace Shopper\Framework\Http\Livewire\Categories;

use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;
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
     * Open reorder Modal.
     *
     * @var bool
     */
    public $reorderModal = false;

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
        (new CategoryRepository())->getById($id)->delete();

        $this->dispatchBrowserEvent('item-removed');
        $this->notify([
            'title' => __("Deleted"),
            'message' => __("The category has successfully removed!")
        ]);
    }

    /**
     * Update category parent position.
     *
     * @param  array  $items
     * @return void
     */
    public function updateGroupOrder($items)
    {
        foreach ($items as $item) {
            (new CategoryRepository())
                ->getById($item['value'])
                ->update(['position' => $item['order']]);
        }

        $this->emitSelf('notify-saved');
    }

    /**
     * Update subcategory position.
     *
     * @param  array  $groups
     * @return void
     */
    public function updateCategoryOrder($groups)
    {
        foreach ($groups as $group) {
            foreach ($group['items'] as $item) {
                (new CategoryRepository())
                    ->getById($item['value'])
                    ->update([
                        'parent_id' => $group['value'],
                        'position'  => $item['order']
                    ]);
            }
        }

        $this->emitSelf('notify-saved');
    }

    /**
     * Close reorder modal.
     *
     * @return void
     */
    public function closeModale()
    {
        $this->reorderModal = false;
    }

    /**
     * Launch reorder modale.
     *
     * @return void
     */
    public function launchModale()
    {
        $this->reorderModal = true;
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
                ->paginate(10),
            'parentCategories' => (new CategoryRepository())
                ->with('childs')
                ->where('parent_id', null)
                ->orderBy('position', 'asc')
                ->get(),
        ]);
    }
}
