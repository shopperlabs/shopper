<?php

namespace Shopper\Framework\Http\Livewire\Orders;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Models\Shop\Order\Order;
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
     * Number of order to display per page.
     *
     * @var int
     */
    public $results = 10;

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
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.orders.browse', [
            'total' => Order::query()->count(),
            'orders' => Order::query()
                ->with('customer')
                ->withCount('items')
                ->where(function (Builder $query) {
                    $query->where('number', 'like', '%'. $this->search .'%')
                        ->orWhere('status', 'like', '%'. $this->search .'%');
                })
                ->paginate($this->results)
        ]);
    }
}
