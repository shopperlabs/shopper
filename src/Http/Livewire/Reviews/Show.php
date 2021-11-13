<?php

namespace Shopper\Framework\Http\Livewire\Reviews;

use Exception;
use Livewire\Component;
use Shopper\Framework\Models\Shop\Review;
use WireUi\Traits\Actions;

class Show extends Component
{
    use Actions;

    public Review $review;
    public bool $approved;

    public function mount(Review $review)
    {
        $this->review = $review->load(['reviewrateable', 'author']);
        $this->approved = $review->approved;
    }

    public function updatedApproved()
    {
        $this->approved = ! $this->review->approved;
        $this->review->update(['approved' => ! $this->review->approved]);

        $this->notification()->success(__('Updated'), __('Review approved status updated!'));
    }

    /**
     * Remove a review from the storage.
     *
     * @throws Exception
     */
    public function remove()
    {
        $this->review->delete();

        session()->flash('success', __('Review removed successfully.'));

        $this->redirectRoute('shopper.reviews.index');
    }

    public function render()
    {
        return view('shopper::livewire.reviews.show');
    }
}
