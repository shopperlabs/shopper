<?php

declare(strict_types=1);

use Shopper\Core\Models\Product;
use Shopper\Core\Models\Review;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

describe(Review::class, function (): void {
    it('belongs to reviewrateable product', function (): void {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        $review = Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $user->id,
            'author_type' => $user->getMorphClass(),
            'rating' => 5,
            'title' => 'Great',
            'content' => 'Great product',
            'approved' => true,
        ]);

        expect($review->reviewrateable)->toBeInstanceOf(Product::class)
            ->and($review->reviewrateable->id)->toBe($product->id);
    });

    it('belongs to author user', function (): void {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        $review = Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $user->id,
            'author_type' => $user->getMorphClass(),
            'rating' => 5,
            'title' => 'Good',
            'content' => 'Good product',
            'approved' => true,
        ]);

        expect($review->author)->toBeInstanceOf(User::class)
            ->and($review->author->id)->toBe($user->id);
    });

    it('has rating and content', function (): void {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        $review = Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $user->id,
            'author_type' => $user->getMorphClass(),
            'rating' => 4,
            'title' => 'Nice',
            'content' => 'Great product!',
            'approved' => true,
        ]);

        expect($review->rating)->toBe(4)
            ->and($review->content)->toBe('Great product!');
    });

    it('can create rating', function (): void {
        $product = Product::factory()->create();
        $user = User::factory()->create();
        $review = new Review;

        $createdReview = $review->createRating($product, [
            'rating' => 5,
            'title' => 'Excellent',
            'content' => 'Best product ever',
            'approved' => true,
        ], $user);

        expect($createdReview)->toBeInstanceOf(Review::class)
            ->and($createdReview->rating)->toBe(5)
            ->and($createdReview->title)->toBe('Excellent')
            ->and($createdReview->author_id)->toBe($user->id);
    });

    it('can update rating', function (): void {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        $review = Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $user->id,
            'author_type' => $user->getMorphClass(),
            'rating' => 3,
            'title' => 'Average',
            'content' => 'OK product',
            'approved' => false,
        ]);

        $reviewInstance = new Review;
        $updatedReview = $reviewInstance->updateRating($review->id, [
            'rating' => 5,
            'title' => 'Excellent',
            'approved' => true,
        ]);

        expect($updatedReview->rating)->toBe(5)
            ->and($updatedReview->title)->toBe('Excellent')
            ->and($updatedReview->approved)->toBeTrue();
    });

    it('can get all ratings for a product', function (): void {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        Review::factory()->count(3)->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $user->id,
            'author_type' => $user->getMorphClass(),
            'approved' => true,
        ]);

        $review = new Review;
        $allRatings = $review->getAllRatings($product->id);

        expect($allRatings)->toHaveCount(3);
    });

    it('can get approved ratings', function (): void {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        Review::factory()->count(2)->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $user->id,
            'author_type' => $user->getMorphClass(),
            'approved' => true,
        ]);

        Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $user->id,
            'author_type' => $user->getMorphClass(),
            'approved' => false,
        ]);

        $review = new Review;
        $approvedRatings = $review->getApprovedRatings($product->id);

        expect($approvedRatings)->toHaveCount(2);
    });

    it('can get not approved ratings', function (): void {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $user->id,
            'author_type' => $user->getMorphClass(),
            'approved' => true,
        ]);

        Review::factory()->count(2)->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $user->id,
            'author_type' => $user->getMorphClass(),
            'approved' => false,
        ]);

        $review = new Review;
        $notApprovedRatings = $review->getNotApprovedRatings($product->id);

        expect($notApprovedRatings)->toHaveCount(2);
    });

    it('can get recent ratings with limit', function (): void {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        Review::factory()->count(10)->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $user->id,
            'author_type' => $user->getMorphClass(),
            'approved' => true,
        ]);

        $review = new Review;
        $recentRatings = $review->getRecentRatings($product->id, 5);

        expect($recentRatings)->toHaveCount(5);
    });

    it('can get recent user ratings', function (): void {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        Review::factory()->count(7)->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $user->id,
            'author_type' => $user->getMorphClass(),
            'approved' => true,
        ]);

        $review = new Review;
        $userRatings = $review->getRecentUserRatings($user->id, 5);

        expect($userRatings)->toHaveCount(5);
    });

    it('can delete rating', function (): void {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        $review = Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $user->id,
            'author_type' => $user->getMorphClass(),
            'approved' => true,
        ]);

        $reviewInstance = new Review;
        $result = $reviewInstance->deleteRating($review->id);

        expect($result)->toBeTrue()
            ->and(Review::find($review->id))->toBeNull();
    });

    it('can update approved status', function (): void {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        $review = Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $user->id,
            'author_type' => $user->getMorphClass(),
            'approved' => false,
        ]);

        $review->updatedApproved(true);

        expect($review->fresh()->approved)->toBeTrue();
    });

    it('casts approved and is_recommended to boolean', function (): void {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        $review = Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $user->id,
            'author_type' => $user->getMorphClass(),
            'approved' => 1,
            'is_recommended' => 1,
        ]);

        expect($review->approved)->toBeTrue()
            ->and($review->approved)->toBeBool()
            ->and($review->is_recommended)->toBeTrue()
            ->and($review->is_recommended)->toBeBool();
    });
})->group('review', 'models');
