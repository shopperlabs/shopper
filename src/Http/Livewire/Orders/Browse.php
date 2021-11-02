<?php

namespace Shopper\Framework\Http\Livewire\Orders;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Models\Shop\Order\Order;
use Shopper\Framework\Models\Shop\Order\OrderStatus;

class Browse extends Component
{
    use WithPagination;

    /**
     * Search.
     *
     * @var null|string
     */
    public $search;

    /**
     * Number of order to display per page.
     */
    public int $perPage = 10;

    /**
     * Order status.
     *
     * @var null|string
     */
    public $status;

    /**
     * Filter start date.
     *
     * @var null|string
     */
    public $dateStart;

    /**
     * Filter end date.
     *
     * @var null|string
     */
    public $dateEnd;

    /**
     * Custom Livewire pagination view.
     */
    public function paginationView(): string
    {
        return 'shopper::livewire.wire-pagination-links';
    }

    /**
     * Reset all filters.
     */
    public function resetFilters(): void
    {
        $this->dateStart = null;
        $this->dateEnd = null;
        $this->status = null;
    }

    /**
     * Archived an order.
     *
     * @throws Exception
     */
    public function archived(int $id): void
    {
        Order::query()->find($id)->delete();

        $this->notify([
            'title' => __('Archived'),
            'message' => __('You have successfully archived this order.'),
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.orders.browse', [
            'total' => Order::query()->count(),
            'orders' => Order::query()
                ->with(['customer', 'items'])
                ->withCount('items')
                ->where(function (Builder $query) {
                    if ($this->search) {
                        $query->where('number', 'like', '%' . $this->search . '%');
                    }

                    if ($this->status) {
                        $query->where('status', '=', $this->status);
                    }

                    if ($this->dateStart & $this->dateEnd) {
                        $query->whereBetween('created_at', [$this->dateStart, $this->dateEnd]);
                    }
                })
                ->orWhereHas('customer', function (Builder $query) {
                    if ($this->search) {
                        $query->where('email', 'like', '%' . $this->search . '%')
                            ->orWhere('first_name', 'like', '%' . $this->search . '%');
                    }
                })
                ->paginate($this->perPage),
            'orderStatus' => OrderStatus::values(),
        ]);
    }
}
