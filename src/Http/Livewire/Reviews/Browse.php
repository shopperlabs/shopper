<?php

namespace Shopper\Framework\Http\Livewire\Reviews;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Shopper\Framework\Models\Shop\Review;

class Browse extends Component
{
    /**
     * Defined if a review is approved.
     *
     * @var bool
     */
    public $approved;

    /**
     * Product search name value.
     *
     * @var string
     */
    public $search;

    /**
     * Reset Filter on status.
     *
     * @return void
     */
    public function resetStatusFilter()
    {
        $this->approved = null;
    }

    /**
     * Remove a review from the storage.
     *
     * @param  int  $id
     * @throws \Exception
     */
    public function remove($id)
    {
        Review::query()->find($id)->delete();

        $this->notify([
           'title' => __("Deleted"),
           'message' => __("Review removed successfully.")
        ]);
        $this->dispatchBrowserEvent('close-review');
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.reviews.browse', [
            'total' => Review::query()->count(),
            'reviews' => Review::with(['reviewrateable', 'author'])
                ->whereHasMorph('reviewrateable', config('shopper.system.models.product'), function (Builder $query) {
                    $query->where('name', 'like', '%'. $this->search .'%');
                })
                ->where(function (Builder $query) {
                    if ($this->approved !== null) {
                        $query->where('approved', boolval($this->approved));
                    }
                })
                ->paginate(8)
        ]);
    }
}
