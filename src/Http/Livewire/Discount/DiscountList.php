<?php

namespace Shopper\Framework\Http\Livewire\Discount;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Models\Discount;
use Shopper\Framework\Repositories\DiscountRepository;

class DiscountList extends Component
{
    use WithPagination;

    public $is_active = '';
    public $date = '';
    public $direction = 'desc';
    public $search = '';

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

    public function resetDate()
    {
        $this->date = '';
    }

    public function changeStatus(string $status)
    {
        $this->is_active = $status;
        $this->dispatchBrowserEvent('change-status');
    }

    public function clear()
    {
        $this->is_active = '';
        $this->dispatchBrowserEvent('change-status');
    }

    public function render()
    {
        $total = (new DiscountRepository())->count();
        $discounts = Discount::where('code', 'like', '%'. $this->search .'%')
            ->where(function (Builder $query) {
                if ($this->is_active !== '') {
                    $query->where('is_active', $this->is_active);
                }

                if ($this->date !== '') {
                    $query->whereDate('date_start', $this->date)
                        ->orWhereDate('date_end', $this->date);
                }
            })
            ->orderBy('created_at', $this->direction)
            ->paginate(8);

        return view('shopper::components.livewire.discounts.index', compact('total', 'discounts'));
    }
}