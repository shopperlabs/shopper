<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Reviews;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Framework\Models\Shop\Review;

class Show extends Component
{
    public Review $review;

    public bool $approved;

    public function mount(Review $review): void
    {
        $this->review = $review->load(['reviewrateable', 'author']);
        $this->approved = $review->approved;
    }

    public function updatedApproved(): void
    {
        $this->approved = ! $this->review->approved;
        $this->review->update(['approved' => ! $this->review->approved]);

        Notification::make()
            ->title(__('shopper::layout.status.updated'))
            ->body(__('shopper::pages/products.reviews.approved_message'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.reviews.show');
    }
}
