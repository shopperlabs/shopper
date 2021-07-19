<?php

namespace Shopper\Framework\Http\Livewire\Discounts;

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
    public $isActive;

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
     * Reset status filter.
     *
     * @return void
     */
    public function resetActiveFilter()
    {
        $this->isActive = null;
    }

    public function render()
    {
        return view('shopper::livewire.discounts.browse', [
            'total' => Discount::query()->count(),
            'discounts' => Discount::query()->where('code', 'like', '%'. $this->search .'%')
                ->where(function (Builder $query) {
                    if ($this->isActive !== null) {
                        $query->where('is_active', boolval($this->isActive));
                    }

                    if ($this->date !== null) {
                        $query->whereDate('start_at', $this->date)
                            ->orWhereDate('end_at', $this->date);
                    }
                })
                ->orderBy($this->sortBy ?? 'created_at', $this->sortDirection)
                ->paginate(8),
        ]);
    }
}
