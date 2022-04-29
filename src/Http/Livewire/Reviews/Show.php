<?php

namespace Shopper\Framework\Http\Livewire\Reviews;

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

        $this->notification()->success(
            __('shopper::layout.status.updated'),
            __('shopper::pages/products.reviews.approved_message')
        );
    }

    public function render()
    {
        return view('shopper::livewire.reviews.show');
    }
}
