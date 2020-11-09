<?php

namespace Shopper\Framework\Http\Livewire\Customers;

use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Repositories\Ecommerce\CustomerRepository;

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
        (new CustomerRepository())->getById($id)->delete();

        $this->dispatchBrowserEvent('customer-removed');
        $this->dispatchBrowserEvent('notify', [
            'title' => __("Deleted"),
            'message' => __("The customer has successfully removed!")
        ]);
    }

    public function render()
    {
        $total = (new CustomerRepository())->count();

        $customers = (new CustomerRepository())
            ->where('last_name', '%'. $this->search .'%', 'like')
            ->orWhere('first_name', '%'. $this->search .'%', 'like')
            ->orderBy('created_at', $this->direction)
            ->paginate(10);

        return view('shopper::livewire.customers.browse', compact('customers', 'total'));
    }
}
