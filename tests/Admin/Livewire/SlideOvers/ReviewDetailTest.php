<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\Review;
use Shopper\Core\Models\User;
use Shopper\Livewire\SlideOvers\ReviewDetail;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
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

    it('can approve review via action', function (): void {
        expect($this->review->approved)->toBeFalse();

        Livewire::test(ReviewDetail::class, ['review' => $this->review])
            ->callAction('approved')
            ->assertRedirect(route('shopper.reviews.index'));

        $this->review->refresh();

        expect($this->review->approved)->toBeTrue();
    });

    it('can disapprove review via action', function (): void {
        $this->review->update(['approved' => true]);

        expect($this->review->approved)->toBeTrue();

        Livewire::test(ReviewDetail::class, ['review' => $this->review])
            ->callAction('approved')
            ->assertRedirect(route('shopper.reviews.index'));

        $this->review->refresh();

        expect($this->review->approved)->toBeFalse();
    });

    it('sends notification after toggling approved status', function (): void {
        Livewire::test(ReviewDetail::class, ['review' => $this->review])
            ->callAction('approved')
            ->assertNotified();
    });

    it('redirects to reviews index after action', function (): void {
        Livewire::test(ReviewDetail::class, ['review' => $this->review])
            ->callAction('approved')
            ->assertRedirect(route('shopper.reviews.index'));
    });
})->group('livewire', 'slideovers', 'products');
