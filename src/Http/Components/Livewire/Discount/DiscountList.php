<?php

namespace Shopper\Framework\Http\Components\Livewire\Discount;

use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Repositories\DiscountRepository;

class DiscountList extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'shopper::components.livewire.wire-pagination-links';
    }

    public function render()
    {
        $total = (new DiscountRepository())->count();
        $discounts = (new DiscountRepository())->get();

        return view('shopper::components.livewire.discounts.index', compact('total', 'discounts'));
    }
}