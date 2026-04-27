<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Review;
use Shopper\Livewire\SlideOvers\ReviewDetail;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {

    $this->user = User::factory()->create();
    $this->user->givePermissionTo(['reviews.browse', 'reviews.edit', 'reviews.delete']);
    $this->actingAs($this->user);

    $this->customer = User::factory()->create();
    $this->product = Product::factory()->create();

    $this->review = Review::factory()->create([
        'author_id' => $this->customer->id,
        'author_type' => User::class,
        'reviewrateable_id' => $this->product->id,
        'reviewrateable_type' => Product::class,
        'approved' => false,
    ]);
});

describe(ReviewDetail::class, function (): void {
    it('can render review detail slideover', function (): void {
        Livewire::test(ReviewDetail::class, ['review' => $this->review])
            ->assertOk();
    });

    it('loads review with author and reviewrateable on mount', function (): void {
        $component = Livewire::test(ReviewDetail::class, ['review' => $this->review]);

        expect($component->get('review'))->not->toBeNull()
            ->and($component->get('review')->id)->toBe($this->review->id)
            ->and($component->get('review')->author)->not->toBeNull()
            ->and($component->get('review')->reviewrateable)->not->toBeNull();
    });

    it('can approve a pending review', function (): void {
        expect($this->review->approved)->toBeFalse();

        Livewire::test(ReviewDetail::class, ['review' => $this->review])
            ->callAction('approve')
            ->assertRedirect(route('shopper.reviews.index'));

        $this->review->refresh();

        expect($this->review->approved)->toBeTrue();
    });

    it('can reject an approved review', function (): void {
        $this->review->update(['approved' => true]);

        expect($this->review->approved)->toBeTrue();

        Livewire::test(ReviewDetail::class, ['review' => $this->review])
            ->callAction('reject')
            ->assertRedirect(route('shopper.reviews.index'));

        $this->review->refresh();

        expect($this->review->approved)->toBeFalse();
    });

    it('can flag a review as spam', function (): void {
        Livewire::test(ReviewDetail::class, ['review' => $this->review])
            ->callAction('markAsSpam')
            ->assertRedirect(route('shopper.reviews.index'))
            ->assertDispatched('review-flagged-as-spam');

        $this->review->refresh();

        expect($this->review->approved)->toBeFalse();
    });

    it('sends notification when approving a review', function (): void {
        Livewire::test(ReviewDetail::class, ['review' => $this->review])
            ->callAction('approve')
            ->assertNotified();
    });

    it('dispatches review-approved event after approval', function (): void {
        Livewire::test(ReviewDetail::class, ['review' => $this->review])
            ->callAction('approve')
            ->assertDispatched('review-approved');
    });

    it('hides the approve action when the user lacks `reviews.edit`', function (): void {
        $browseOnly = User::factory()->create();
        $browseOnly->givePermissionTo('reviews.browse');
        $this->actingAs($browseOnly);

        Livewire::test(ReviewDetail::class, ['review' => $this->review])
            ->assertActionHidden('approve')
            ->assertActionHidden('reject');
    });

    it('hides the markAsSpam action when the user lacks `reviews.delete`', function (): void {
        $editor = User::factory()->create();
        $editor->givePermissionTo(['reviews.browse', 'reviews.edit']);
        $this->actingAs($editor);

        Livewire::test(ReviewDetail::class, ['review' => $this->review])
            ->assertActionHidden('markAsSpam');
    });
})->group('livewire', 'slideovers', 'products');
