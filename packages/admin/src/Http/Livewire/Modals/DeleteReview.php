<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Models\Shop\Review;

class DeleteReview extends ModalComponent
{
    public int $reviewID;

    public function mount(int $reviewID): void
    {
        $this->reviewID = $reviewID;
    }

    public function delete(): void
    {
        Review::query()->find($this->reviewID)->delete();

        session()->flash('success', __('shopper::pages/products.reviews.modal.success_message'));

        $this->redirectRoute('shopper.reviews.index');
    }

    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.delete-review');
    }
}
