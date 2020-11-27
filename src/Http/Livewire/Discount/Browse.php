<?php

namespace Shopper\Framework\Http\Livewire\Discount;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Shopper\Framework\Models\Shop\Discount;
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
     * Discount active state.
     *
     * @var string
     */
    public $is_active;

    /**
     * Start/End Date of the discount
     *
     * @var string
     */
    public $date;

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
     * Reset date filter.
     *
     * @return void
     */
    public function resetDate()
    {
        $this->date = null;
    }

    /**
     * Discount filter status.
     *
     * @param  string  $status
     */
    public function changeStatus(string $status)
    {
        $this->is_active = $status;
        $this->dispatchBrowserEvent('change-status');
    }

    /**
     * Reset status filter.
     *
     * @return void
     */
    public function clear()
    {
        $this->is_active = null;
        $this->dispatchBrowserEvent('change-status');
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.discounts.browse', [
            'total' => Discount::query()->count(),
            'discounts' => Discount::query()->where('code', 'like', '%'. $this->search .'%')
                ->where(function (Builder $query) {
                    if ($this->is_active !== null) {
                        $query->where('is_active', boolval($this->is_active));
                    }

                    if ($this->date !== null) {
                        $query->whereDate('date_start', $this->date)
                            ->orWhereDate('date_end', $this->date);
                    }
                })
                ->orderBy($this->sortBy ?? 'created_at', $this->sortDirection)
                ->paginate(8)
        ]);
    }
}
