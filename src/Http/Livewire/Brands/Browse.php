<?php

namespace Shopper\Framework\Http\Livewire\Brands;

use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;

class Browse extends Component
{
    use WithPagination;

    public string $search = '';

    public function paginationView(): string
    {
        return 'shopper::livewire.wire-pagination-links';
    }

    public function remove(int $id)
    {
        (new BrandRepository())->getById($id)->delete();

        $this->dispatchBrowserEvent('brand-removed');
        $this->dispatchBrowserEvent('notify', [
            'title' => __('Deleted'),
            'message' => __('The brand has successfully removed!'),
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.brands.browse', [
            'total' => (new BrandRepository())->count(),
            'brands' => (new BrandRepository())
                ->where('name', '%' . $this->search . '%', 'like')
                ->paginate(8),
        ]);
    }
}
