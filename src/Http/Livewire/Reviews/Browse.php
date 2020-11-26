<?php

namespace Shopper\Framework\Http\Livewire\Reviews;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Shopper\Framework\Models\Shop\Review;

class Browse extends Component
{
    /**
     * User that make review.
     *
     * @var null
     */
    public $reviewer = null;

    /**
     * @var bool
     */
    public $process = false;

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
     * Display a user
     *
     * @param  int  $id
     * @return void
     */
    public function show($id)
    {
        $this->process = true;
        $this->reviewer = Review::with(['reviewrateable', 'author'])->find($id);

        $this->dispatchBrowserEvent('open-review');
    }

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
     * Toggle review status to approve/unapproved.
     *
     * @param  int  $id
     * @return void
     */
    public function toggleApproved($id)
    {
        $review = Review::query()
            ->find($id)
            ->update(['approved' => !$this->reviewer->approved]);

        $this->notify([
            'title' => __("Deleted"),
            'message' => __("Review status updated.")
        ]);
        $this->dispatchBrowserEvent('close-review');

        $this->process = false;
        $this->reviewer = $review;
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

        $this->process = false;
        $this->reviewer = null;

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
                ->where(function (Builder $query) {
                    if ($this->approved) {
                        $query->where('approved', boolval($this->approved));
                    }
                })
                ->paginate(8)
        ]);
    }
}
