<?php

namespace Shopper\Framework\Components\Livewire\Brands;

use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;

class Browse extends Component
{
    use WithPagination;

    /**
     * Search.
     *
     * @var string
     */
    public $search = '';

    /**
     * Sort direction.
     *
     * @var string
     */
    public $direction = 'desc';

    public function paginationView()
    {
        return 'shopper::livewire.wire-pagination-links';
    }

    /**
     * Sort results.
     *
     * @param  string  $value
     */
    public function sort($value)
    {
        $this->direction = $value === 'asc' ? 'desc' : 'asc';
    }

    public function remove(int $id)
    {
        (new BrandRepository())->getById($id)->delete();

        $this->dispatchBrowserEvent('brand-removed');
        $this->dispatchBrowserEvent('notify', [
            'title' => __("Deleted"),
            'message' => __("The brand has successfully removed!")
        ]);
    }

    public function render()
    {
        $total = (new BrandRepository())->count();

        $brands = (new BrandRepository())
            ->where('name', '%'. $this->search .'%', 'like')
            ->orderBy('created_at', $this->direction)
            ->paginate(10);

        return view('shopper::livewire.brands.browse', compact('brands', 'total'));
    }
}
