<?php

namespace Shopper\Framework\Http\Livewire\Reviews;

use Livewire\Component;
use Shopper\Framework\Models\Shop\Review;

class Show extends Component
{
    /**
     * Review to show.
     *
     * @var Review
     */
    public Review $review;

    /**
     * Review approved state.
     *
     * @var bool
     */
    public $approved;

    /**
     * Component Mount method instance.
     *
     * @param  Review  $review
     */
    public function mount(Review $review)
    {
        $this->review = $review->load(['reviewrateable', 'author']);
        $this->approved = $review->approved;
    }

    /**
     * Toggle review approved state.
     *
     * @return void
     */
    public function updatedApproved()
    {
        $this->approved = !$this->review->approved;
        $this->review->update(['approved' => !$this->review->approved]);

        $this->notify(['title' => __('Updated'), 'message' => __("Review approved status updated.")]);
    }

    /**
     * Remove a review from the storage.
     *
     * @throws \Exception
     */
    public function remove()
    {
        $this->review->delete();

        session()->flash('success', __("Review removed successfully."));
        $this->redirectRoute('shopper.reviews.index');
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('shopper::livewire.reviews.show');
    }
}
