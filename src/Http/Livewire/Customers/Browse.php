<?php

namespace Shopper\Framework\Http\Livewire\Customers;

use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Shopper\Framework\Traits\WithSorting;
use Shopper\Framework\Repositories\UserRepository;

class Browse extends Component
{
    use WithPagination;

    use WithSorting;

    /**
     * Search.
     *
     * @var string
     */
    public $search = '';

    /**
     * Filter by email subscription.
     *
     * @var bool
     */
    public $emailSubscription;

    /**
     * Filter by active account.
     *
     * @var bool
     */
    public $activeAccount;

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
     * Reset Filter on email subscription.
     */
    public function resetEmailFilter()
    {
        $this->emailSubscription = null;
    }

    /**
     * Reset Filter on active account.
     */
    public function resetActiveAccountFilter()
    {
        $this->activeAccount = null;
    }

    /**
     * Remove a record to the database.
     *
     * @throws Exception
     */
    public function remove(int $id)
    {
        (new UserRepository())->getById($id)->delete();

        $this->dispatchBrowserEvent('item-removed');
        $this->dispatchBrowserEvent('notify', [
            'title' => __('Deleted'),
            'message' => __('The customer has successfully removed!'),
        ]);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     *
     * @throws \Shopper\Framework\Exceptions\GeneralException
     */
    public function render()
    {
        return view('shopper::livewire.customers.browse', [
            'total' => (new UserRepository())
                ->makeModel()
                ->whereHas('roles', function (Builder $query) {
                    $query->where('name', config('shopper.system.users.default_role'));
                })
                ->count(),
            'customers' => (new UserRepository())
                ->makeModel()
                ->whereHas('roles', function (Builder $query) {
                    $query->where('name', config('shopper.system.users.default_role'));
                })
                ->where(function (Builder $query) {
                    $query->where('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('first_name', 'like', '%' . $this->search . '%');
                })
                ->where(function (Builder $query) {
                    if ($this->emailSubscription !== null) {
                        $query->where('opt_in', $this->emailSubscription);
                    }
                })
                ->where(function (Builder $query) {
                    if ($this->activeAccount === '1') {
                        $query->whereNotNull('email_verified_at');
                    }

                    if ($this->activeAccount === '0') {
                        $query->whereNull('email_verified_at');
                    }
                })
                ->orderBy($this->sortBy ?? 'created_at', $this->sortDirection)
                ->paginate(10),
        ]);
    }
}
