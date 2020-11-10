<?php

namespace Shopper\Framework\Http\Livewire\Customers;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Repositories\Ecommerce\CustomerRepository;
use Shopper\Framework\Traits\WithSorting;

class Browse extends Component
{
    use WithPagination, WithSorting;

    /**
     * Search.
     *
     * @var string
     */
    public $search = '';

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
        (new CustomerRepository())->getById($id)->delete();

        $this->dispatchBrowserEvent('item-removed');
        $this->dispatchBrowserEvent('notify', [
            'title' => __("Deleted"),
            'message' => __("The customer has successfully removed!")
        ]);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.customers.browse', [
            'total' => (new CustomerRepository())
                ->makeModel()
                ->whereHas('roles', function (Builder $query) {
                    $query->where('name', config('shopper.system.users.default_role'));
                })
                ->count(),
            'customers' => (new CustomerRepository())
                ->makeModel()
                ->whereHas('roles', function (Builder $query) {
                    $query->where('name', config('shopper.system.users.default_role'));
                })
                ->where('last_name', '%'. $this->search .'%', 'like')
                ->orWhere('first_name', '%'. $this->search .'%', 'like')
                ->orderBy($this->sortBy ?? 'last_name', $this->sortDirection)
                ->paginate(10),
        ]);
    }
}
