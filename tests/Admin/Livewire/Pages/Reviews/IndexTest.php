<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Shopper\Core\Models\Review;
use Shopper\Livewire\Pages\Reviews\Index;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\ProductVariant;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('reviews.browse');
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
            ->loadTable()
            ->assertCanSeeTableRecords(Review::limit(3)->get());
    });

    it('filters reviews by pending tab', function (): void {
        $product = Product::factory()->create();
        $author = User::factory()->create();

        $approved = Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
            'approved' => true,
        ]);

        $pending = Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
            'approved' => false,
        ]);

        Livewire::test(Index::class)
            ->set('activeTab', 'pending')
            ->loadTable()
            ->assertCanSeeTableRecords([$pending])
            ->assertCanNotSeeTableRecords([$approved]);
    });

    it('filters reviews by approved tab', function (): void {
        $product = Product::factory()->create();
        $author = User::factory()->create();

        $approved = Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
            'approved' => true,
        ]);

        $pending = Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
            'approved' => false,
        ]);

        Livewire::test(Index::class)
            ->set('activeTab', 'approved')
            ->loadTable()
            ->assertCanSeeTableRecords([$approved])
            ->assertCanNotSeeTableRecords([$pending]);
    });

    it('computes stats with empty reviews table', function (): void {
        $component = Livewire::test(Index::class);
        $stats = $component->instance()->stats;

        expect($stats)
            ->toMatchArray([
                'total' => 0,
                'pending' => 0,
                'average' => 0.0,
                'five_star_percent' => 0,
            ]);
    });

    it('computes stats with mixed reviews', function (): void {
        $product = Product::factory()->create();
        $author = User::factory()->create();

        Review::factory()->count(2)->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
            'rating' => 5,
            'approved' => true,
        ]);

        Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
            'rating' => 3,
            'approved' => false,
        ]);

        Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
            'rating' => 1,
            'approved' => false,
        ]);

        $component = Livewire::test(Index::class);
        $stats = $component->instance()->stats;

        expect($stats['total'])->toBe(4)
            ->and($stats['pending'])->toBe(2)
            ->and($stats['average'])->toBe(3.5)
            ->and($stats['five_star_percent'])->toBe(50);
    });

    it('computes a rating breakdown across all stars', function (): void {
        $product = Product::factory()->create();
        $author = User::factory()->create();

        Review::factory()->count(3)->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
            'rating' => 5,
        ]);

        Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
            'rating' => 1,
        ]);

        $breakdown = Livewire::test(Index::class)->instance()->ratingBreakdown;

        expect($breakdown)->toHaveCount(5)
            ->and($breakdown[0])->toMatchArray(['rating' => 5, 'count' => 3, 'percent' => 75])
            ->and($breakdown[4])->toMatchArray(['rating' => 1, 'count' => 1, 'percent' => 25]);
    });

    it('computes the recommended percentage', function (): void {
        $product = Product::factory()->create();
        $author = User::factory()->create();

        Review::factory()->count(3)->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
            'is_recommended' => true,
        ]);

        Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
            'is_recommended' => false,
        ]);

        expect(Livewire::test(Index::class)->instance()->recommendedPercent)
            ->toBe(75);
    });

    it('returns zero recommended when no reviews exist', function (): void {
        expect(Livewire::test(Index::class)->instance()->recommendedPercent)
            ->toBe(0);
    });

    it('sorts reviews by product and customer name', function (): void {
        $author = User::factory()->create();

        foreach (['Zebra Mug', 'Apple Case'] as $name) {
            $product = Product::factory()->create(['name' => $name]);

            Review::factory()->create([
                'reviewrateable_id' => $product->id,
                'reviewrateable_type' => $product->getMorphClass(),
                'author_id' => $author->id,
                'author_type' => $author->getMorphClass(),
            ]);
        }

        Livewire::test(Index::class)
            ->loadTable()
            ->sortTable('reviewrateable.name')
            ->assertOk()
            ->sortTable('author.full_name')
            ->assertOk();
    });

    it('searches reviews by product and customer name', function (): void {
        $author = User::factory()->create(['first_name' => 'Zinedine']);
        $otherAuthor = User::factory()->create(['first_name' => 'Karim']);
        $product = Product::factory()->create(['name' => 'Zebra Mug']);
        $otherProduct = Product::factory()->create(['name' => 'Apple Case']);

        $match = Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
        ]);

        $other = Review::factory()->create([
            'reviewrateable_id' => $otherProduct->id,
            'reviewrateable_type' => $otherProduct->getMorphClass(),
            'author_id' => $otherAuthor->id,
            'author_type' => $otherAuthor->getMorphClass(),
        ]);

        $variantReview = Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => (new ProductVariant)->getMorphClass(),
            'author_id' => $otherAuthor->id,
            'author_type' => $otherAuthor->getMorphClass(),
        ]);

        Livewire::test(Index::class)
            ->loadTable()
            ->searchTable('zebra')
            ->assertCanSeeTableRecords([$match])
            ->assertCanNotSeeTableRecords([$other, $variantReview])
            ->searchTable('zinedine')
            ->assertCanSeeTableRecords([$match])
            ->assertCanNotSeeTableRecords([$other]);
    });

    it('filters reviews by product and customer', function (): void {
        $author = User::factory()->create(['first_name' => 'Zinedine', 'last_name' => 'Zidane']);
        $otherAuthor = User::factory()->create(['first_name' => 'Karim']);
        $product = Product::factory()->create(['name' => 'Zebra Mug']);
        $otherProduct = Product::factory()->create(['name' => 'Apple Case']);

        $match = Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
        ]);

        $other = Review::factory()->create([
            'reviewrateable_id' => $otherProduct->id,
            'reviewrateable_type' => $otherProduct->getMorphClass(),
            'author_id' => $otherAuthor->id,
            'author_type' => $otherAuthor->getMorphClass(),
        ]);

        $variantReview = Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => (new ProductVariant)->getMorphClass(),
            'author_id' => $otherAuthor->id,
            'author_type' => $otherAuthor->getMorphClass(),
        ]);

        Livewire::test(Index::class)
            ->loadTable()
            ->filterTable('reviewrateable_id', [$product->id])
            ->assertCanSeeTableRecords([$match])
            ->assertCanNotSeeTableRecords([$other, $variantReview])
            ->assertSeeText(__('shopper::words.product').': Zebra Mug')
            ->resetTableFilters()
            ->filterTable('author_id', $author->id)
            ->assertCanSeeTableRecords([$match])
            ->assertCanNotSeeTableRecords([$other])
            ->assertSeeText(__('shopper::words.customer').': Zinedine Zidane');
    });

    it('searches the product and customer filters among reviewed records only', function (): void {
        $author = User::factory()->create(['first_name' => 'Zinedine']);
        User::factory()->create(['first_name' => 'Zinedane']);
        $product = Product::factory()->create(['name' => 'Zebra Mug']);
        Product::factory()->create(['name' => 'Zebra Case']);

        Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
        ]);

        $table = Livewire::test(Index::class)->loadTable()->instance()->getTable();
        $products = $table->getFilter('reviewrateable_id');
        $authors = $table->getFilter('author_id');

        expect($products->evaluate($products->getSearchResultsUsingCallback(), ['search' => 'zebra']))
            ->toBe([$product->id => 'Zebra Mug'])
            ->and($authors->evaluate($authors->getSearchResultsUsingCallback(), ['search' => 'zined']))
            ->toBe([$author->id => $author->full_name]);
    });

    it('lists reviews whose product was deleted', function (): void {
        $product = Product::factory()->create();

        $review = Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $this->user->id,
            'author_type' => $this->user->getMorphClass(),
        ]);

        $product->delete();

        Livewire::test(Index::class)
            ->loadTable()
            ->assertCanSeeTableRecords([$review]);
    });

    it('renders the filters without loading every review', function (): void {
        $product = Product::factory()->create();

        User::factory()->count(30)->create()->each(fn (User $author) => Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
        ]));

        $retrieved = 0;
        Event::listen('eloquent.retrieved: '.Review::class, function () use (&$retrieved): void {
            $retrieved++;
        });

        Livewire::test(Index::class)->loadTable()->assertOk();

        expect($retrieved)->toBeLessThan(30);
    });
})->group('livewire', 'reviews');
