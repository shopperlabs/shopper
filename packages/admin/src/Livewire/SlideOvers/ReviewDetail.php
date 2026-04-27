<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\Size;
use Illuminate\Contracts\View\View;
use Laravelcm\LivewireSlideOvers\SlideOverComponent;
use Livewire\Attributes\Locked;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Review;
use Shopper\Traits\HandlesAuthorizationExceptions;

class ReviewDetail extends SlideOverComponent implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    #[Locked]
    public Review $review;

    public function mount(): void
    {
        $this->authorize('reviews.browse');

        $this->review->load('author', 'reviewrateable');
    }

    public function approveAction(): Action
    {
        return Action::make('approve')
            ->label(__('shopper::pages/reviews.actions.approve'))
            ->icon(Untitledui::CheckCircle)
            ->size(Size::Small)
            ->authorize('reviews.edit')
            ->action(function (): void {
                $this->review->updatedApproved(true);

                Notification::make()
                    ->title(__('shopper::pages/reviews.actions.approved_message'))
                    ->success()
                    ->send();

                $this->dispatch('review-approved', reviewId: $this->review->id);

                $this->redirectRoute(name: 'shopper.reviews.index', navigate: true);
            });
    }

    public function rejectAction(): Action
    {
        return Action::make('reject')
            ->label(__('shopper::pages/reviews.actions.reject'))
            ->icon(Untitledui::XClose)
            ->size(Size::Small)
            ->color('gray')
            ->authorize('reviews.edit')
            ->action(function (): void {
                $this->review->updatedApproved(false);

                Notification::make()
                    ->title(__('shopper::pages/reviews.actions.rejected_message'))
                    ->success()
                    ->send();

                $this->dispatch('review-rejected', reviewId: $this->review->id);

                $this->redirectRoute(name: 'shopper.reviews.index', navigate: true);
            });
    }

    public function markAsSpamAction(): Action
    {
        return Action::make('markAsSpam')
            ->label(__('shopper::pages/reviews.actions.mark_as_spam'))
            ->icon(Untitledui::SlashOctagon)
            ->size(Size::Small)
            ->color('danger')
            ->requiresConfirmation()
            ->modalDescription(__('shopper::pages/reviews.actions.spam_confirmation'))
            ->authorize('reviews.delete')
            ->action(function (): void {
                $this->review->updatedApproved(false);

                Notification::make()
                    ->title(__('shopper::pages/reviews.actions.spam_message'))
                    ->success()
                    ->send();

                $this->dispatch('review-flagged-as-spam', reviewId: $this->review->id);

                $this->redirectRoute(name: 'shopper.reviews.index', navigate: true);
            });
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.review-detail');
    }
}
