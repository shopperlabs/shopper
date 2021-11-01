<?php

namespace Shopper\Framework\Http\Livewire\Settings\Attributes;

use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Models\Shop\Product\Attribute;

class Browse extends Component
{
    use WithPagination;

    /**
     * Search input.
     *
     * @var string
     */
    public $search;

    /**
     * Custom Livewire pagination view.
     *
     * @return string
     */
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
        Attribute::query()->find($id)->delete();

        $this->dispatchBrowserEvent('item-removed');

        $this->notify([
            'title' => __('Deleted'),
            'message' => __('The :item has successfully removed!', ['item' => 'attribute']),
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.settings.attributes.browse', [
            'total' => Attribute::query()->count(),
            'attributes' => Attribute::query()
                ->where('name', 'like', '%' . $this->search . '%')
                ->orderBy('name')
                ->paginate(8),
        ]);
    }
}
