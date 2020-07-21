<?php

namespace Shopper\Framework\Http\Components\Livewire\Review;

use Livewire\Component;
use Shopper\Framework\Models\Review;

class ReviewList extends Component
{
    public $reviewer = null;
    public $process = false;

    public function show($id)
    {
        $this->process = true;
        $this->reviewer = Review::with(['reviewrateable', 'author'])->find($id);
        $this->dispatchBrowserEvent('open-review');
    }

    public function toggleApproved($id)
    {
        $review = Review::find($id)->update(['approved' => !$this->reviewer->approved]);

        session()->flash('success', __("Review status updated."));
        $this->dispatchBrowserEvent('close-review');

        $this->process = false;
        $this->reviewer = $review;
    }

    public function remove($id)
    {
        Review::find($id)->delete();


        $this->process = false;
        $this->reviewer = null;

        session()->flash('success', __("Review removed successfully."));
        $this->dispatchBrowserEvent('close-review');
    }

    public function render()
    {
        $reviews = Review::with(['reviewrateable', 'author'])->get();
        $approved = Review::with(['reviewrateable', 'author'])->where('approved', true)->get();
        $pending = Review::with(['reviewrateable', 'author'])->where('approved', false)->get();

        return view('shopper::components.livewire.reviews.list', compact('reviews', 'pending', 'approved'));
    }
}
