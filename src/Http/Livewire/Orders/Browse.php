<?php

namespace Shopper\Framework\Http\Livewire\Orders;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Models\Shop\Order\Order;
use Shopper\Framework\Models\Shop\Order\OrderStatus;
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
    public $perPage = 10;

    /**
     * Order status.
     *
     * @var string
     */
    public $status;

    /**
     * Filter start date.
     *
     * @var string
     */
    public $dateStart;

    /**
     * Filter end date.
     *
     * @var string
     */
    public $dateEnd;

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
     * Reset all filters.
     *
     * @return void
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
     * @param  int  $id
     * @throws \Exception
     * @return void
     */
    public function archived(int $id): void
    {
        Order::query()->find($id)->delete();

        $this->notify([
            'title' => __('Archived'),
            'message' => __('You have successfully archived this order.')
        ]);
    }

    /**
     * Return status style classes.
     *
     * @param  string  $status
     * @return string
     */
    public function statusClasses(string $status): string
    {
        switch ($status) {
            case OrderStatus::PENDING:
                return 'border-yellow-200 bg-yellow-100 text-yellow-800';
            case OrderStatus::REGISTER:
                return 'border-blue-200 bg-blue-100 text-blue-800';
            case OrderStatus::PAID:
                return 'border-green-200 bg-green-100 text-green-800';
            case OrderStatus::CANCELLED:
                return 'border-red-200 bg-red-100 text-red-800';
        }
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
                ->with(['customer', 'items'])
                ->withCount('items')
                ->where(function (Builder $query) {
                    if ($this->search) {
                        $query->where('number', 'like', '%'. $this->search .'%');
                    }

                    if ($this->status) {
                        $query->where('status', $this->status);
                    }

                    if ($this->dateStart & $this->dateEnd) {
                        $query->whereBetween('created_at', [$this->dateStart, $this->dateEnd]);
                    }
                })
                ->orWhereHas('customer', function (Builder $query) {
                    $query->where('email', 'like', '%'. $this->search .'%')
                        ->orWhere('first_name', 'like', '%'. $this->search .'%');
                })
                ->paginate($this->perPage),
            'orderStatus' => OrderStatus::values(),
        ]);
    }
}
