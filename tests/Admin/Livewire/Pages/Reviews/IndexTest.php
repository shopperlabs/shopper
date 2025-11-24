<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Review;
use Shopper\Livewire\Pages\Reviews\Index;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    config()->set('shopper.models.product', Product::class);

    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe(Index::class, function (): void {
    it('can render reviews index component', function (): void {
        Livewire::test(Index::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.reviews.index');
    });

    it('can list reviews in table', function (): void {
        $product = Product::factory()->create();
        $author = User::factory()->create();

        Review::factory()->count(3)->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
        ]);

        Livewire::test(Index::class)
            ->assertCanSeeTableRecords(Review::limit(3)->get());
    });

    it('can filter reviews by approved status', function (): void {
        $product = Product::factory()->create();
        $author = User::factory()->create();

        Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
            'approved' => true,
        ]);

        Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
            'approved' => false,
        ]);

        Livewire::test(Index::class)
            ->filterTable('approved', true)
            ->assertCountTableRecords(1);
    });
})->group('livewire', 'reviews');
