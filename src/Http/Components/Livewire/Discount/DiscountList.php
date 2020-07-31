<?php

namespace Shopper\Framework\Http\Components\Livewire\Discount;

use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Repositories\DiscountRepository;

class DiscountList extends Component
{
    use WithPagination;

    public $search = '';
    public $direction = 'desc';

    public function paginationView()
    {
        return 'shopper::components.livewire.wire-pagination-links';
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

    public function render()
    {
        $total = (new DiscountRepository())->count();
        $discounts = (new DiscountRepository())
            ->where('code', '%'. $this->search .'%', 'like')
            ->orderBy('created_at', $this->direction)
            ->paginate(8);

        $activeDiscounts = (new DiscountRepository())
            ->where('is_active', true)
            ->where('date_start', now(), '<=')
            ->where('date_end', now(), '>=')
            ->orderBy('created_at', $this->direction)
            ->limit(8)
            ->get();

        return view('shopper::components.livewire.discounts.index', compact('total', 'discounts', 'activeDiscounts'));
    }
}