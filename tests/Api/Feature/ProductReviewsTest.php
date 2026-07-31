<?php

declare(strict_types=1);

use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\Review;
use Tests\Core\Stubs\User;

uses(Tests\Api\TestCase::class);

function reviewedProduct(): Product
{
    return Product::factory()->publish()->create(['type' => ProductType::Standard]);
}

function approvedReview(Product $product, array $attributes = [], ?User $author = null): Review
{
    return Review::factory()->create($attributes + [
        'reviewrateable_id' => $product->id,
        'reviewrateable_type' => $product->getMorphClass(),
        'author_id' => ($author ?? User::factory()->create())->id,
        'author_type' => User::class,
        'approved' => true,
    ]);
}

it('lists only approved reviews for a product with an anonymized author', function (): void {
    $product = reviewedProduct();
    $author = User::factory()->create(['first_name' => 'Arthur', 'last_name' => 'Monney']);

    $review = approvedReview($product, ['rating' => 5, 'title' => 'Great'], $author);
    approvedReview($product, ['approved' => false, 'title' => 'Pending']);

    $response = $this->getJson('/store/products/'.$product->slug.'/reviews')
        ->assertOk()
        ->assertHeader('Content-Type', 'application/vnd.api+json')
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.type', 'reviews')
        ->assertJsonPath('data.0.id', $review->refresh()->public_id)
        ->assertJsonPath('data.0.attributes.title', 'Great')
        ->assertJsonPath('data.0.attributes.rating', 5)
        ->assertJsonPath('data.0.attributes.author_name', 'Arthur M.');

    $attributes = $response->json('data.0.attributes');

    expect($attributes)->not->toHaveKeys(['author_id', 'author_type', 'approved'])
        ->and($response->json('data.0.id'))->not->toBe((string) $review->id);
});

it('returns count, average and star distribution over the same approved predicate', function (): void {
    $product = reviewedProduct();

    foreach ([5, 5, 4, 2] as $rating) {
        approvedReview($product, ['rating' => $rating]);
    }

    approvedReview($product, ['rating' => 1, 'approved' => false]);

    $this->getJson('/store/products/'.$product->slug.'/reviews?page[size]=2')
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('meta.reviews_count', 4)
        ->assertJsonPath('meta.average_rating', 4)
        ->assertJsonPath('meta.rating_distribution.5', 2)
        ->assertJsonPath('meta.rating_distribution.4', 1)
        ->assertJsonPath('meta.rating_distribution.2', 1)
        ->assertJsonPath('meta.rating_distribution.1', 0);
});

it('filters by rating and sorts through the review allowlist', function (): void {
    $product = reviewedProduct();

    approvedReview($product, ['rating' => 5]);
    approvedReview($product, ['rating' => 3]);
    approvedReview($product, ['rating' => 5]);

    $this->getJson('/store/products/'.$product->slug.'/reviews?filter[rating]=5')
        ->assertOk()
        ->assertJsonCount(2, 'data');

    $ratings = collect(
        $this->getJson('/store/products/'.$product->slug.'/reviews?sort=rating')->assertOk()->json('data')
    )->pluck('attributes.rating');

    expect($ratings->toArray())->toBe([3, 5, 5]);
});

it('returns a 404 for an unpublished product reviews listing', function (): void {
    $product = Product::factory()->create(['type' => ProductType::Standard, 'is_visible' => false]);

    $this->getJson('/store/products/'.$product->slug.'/reviews')->assertNotFound();
});

it('caps the latest reviews endpoint and never paginates it', function (): void {
    config(['shopper.api.resources.review.latest_limit' => 3]);

    $product = reviewedProduct();

    foreach (range(1, 5) as $i) {
        approvedReview($product, ['rating' => 5]);
    }

    approvedReview($product, ['approved' => false]);

    $response = $this->getJson('/store/reviews?page[size]=50')
        ->assertOk()
        ->assertJsonCount(3, 'data');

    expect($response->json('links.next'))->toBeNull();
});

it('anonymizes gracefully when the author is missing', function (): void {
    $product = reviewedProduct();
    $review = approvedReview($product);

    $review->author()->delete();

    $this->getJson('/store/products/'.$product->slug.'/reviews')
        ->assertOk()
        ->assertJsonPath('data.0.attributes.author_name', null);
});
