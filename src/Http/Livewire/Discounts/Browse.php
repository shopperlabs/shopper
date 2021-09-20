<?php

namespace Shopper\Framework\Http\Livewire\Discounts;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Shopper\Framework\Traits\WithSorting;
use Shopper\Framework\Models\Shop\Discount;

class Browse extends Component
{
    use WithPagination;

    use WithSorting;

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
     * Start/End Date of the discount.
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
     */
    public function resetDate()
    {
        $this->date = null;
    }

    /**
     * Reset status filter.
     */
    public function resetActiveFilter()
    {
        $this->isActive = null;
    }

    public function render()
    {
        return view('shopper::livewire.discounts.browse', [
            'total' => Discount::query()->count(),
            'discounts' => Discount::query()->where('code', 'like', '%' . $this->search . '%')
                ->where(function (Builder $query) {
                    if ($this->isActive !== null) {
                        $query->where('is_active', (bool) ($this->isActive));
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
