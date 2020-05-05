<?php

namespace Shopper\Framework\Http\Components\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Repositories\UserRepository;

class CustomerList extends Component
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

    /**
     * @var string|null
     */
    public $verified = null;

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
        $customers = (new UserRepository())
            ->makeModel()
            ->whereHas('roles', function (Builder $query) {
                $query->where('name', config('shopper.users.default_role'));
            })
            ->where('first_name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', $this->direction)
            ->paginate(10);

        return view('shopper::components.livewire.customers.list', compact('customers'));
    }
}
