<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Brand;
use Shopper\Core\Models\Category;
use Shopper\Core\Models\Channel;
use Shopper\Core\Models\Collection;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\DiscountDetail;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\InventoryHistory;
use Shopper\Core\Models\Price;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductVariant;
use Shopper\Core\Models\Review;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->inventory = Inventory::factory()->create(['is_default' => true]);
    $this->product = Product::factory()->create([
        'is_visible' => false,
        'published_at' => now()->addDay(),
    ]);
});

describe(Product::class, function (): void {
    it('checks if product is in stock', function (): void {
        $this->product->mutateStock($this->inventory->id, 10);

        expect($this->product->inStock())->toBeTrue()
            ->and($this->product->inStock(5))->toBeTrue()
            ->and($this->product->inStock(15))->toBeFalse();
    });

    it('checks if product is not in stock', function (): void {
        expect($this->product->inStock())->toBeFalse();
    });

    it('gets current stock', function (): void {
        $this->product->mutateStock($this->inventory->id, 10);

        expect($this->product->getStock())->toBe(10)
            ->and($this->product->stock)->toBe(10);
    });

    it('gets stock at specific date', function (): void {
        Carbon::setTestNow('2024-01-01 10:00:00');
        $this->product->mutateStock($this->inventory->id, 10);

        Carbon::setTestNow('2024-01-02 10:00:00');
        $this->product->mutateStock($this->inventory->id, 5, oldQuantity: 10);

        $stockAtFirstDate = $this->product->getStock(Carbon::parse('2024-01-01 12:00:00'));
        $stockAtSecondDate = $this->product->getStock(Carbon::parse('2024-01-02 12:00:00'));

        expect($stockAtFirstDate)->toBe(10)
            ->and($stockAtSecondDate)->toBe(15);

        Carbon::setTestNow();
    });

    it('gets stock for specific inventory', function (): void {
        $inventory1 = Inventory::factory()->create();
        $inventory2 = Inventory::factory()->create();

        $this->product->mutateStock($inventory1->id, 10);
        $this->product->mutateStock($inventory2->id, 5);

        expect($this->product->stockInventory($inventory1->id))->toBe(10)
            ->and($this->product->stockInventory($inventory2->id))->toBe(5);
    });

    it('mutates stock by adding quantity', function (): void {
        $history = $this->product->mutateStock($this->inventory->id, 10);

        expect($history)->toBeInstanceOf(InventoryHistory::class)
            ->and($history->quantity)->toBe(10)
            ->and($this->product->stock)->toBe(10);
    });

    it('decreases stock', function (): void {
        $this->product->mutateStock($this->inventory->id, 20);
        $history = $this->product->decreaseStock($this->inventory->id, 5, oldQuantity: 20);

        expect($history)->toBeInstanceOf(InventoryHistory::class)
            ->and($history->quantity)->toBe(-5)
            ->and($this->product->stock)->toBe(15);
    });

    it('clears all stock', function (): void {
        $this->product->mutateStock($this->inventory->id, 10);

        $result = $this->product->clearStock();

        expect($result)->toBeTrue()
            ->and($this->product->stock)->toBe(0)
            ->and($this->product->inventoryHistories()->count())->toBe(0);
    });

    it('clears stock and sets new quantity', function (): void {
        $this->product->mutateStock($this->inventory->id, 10);

        $this->product->clearStock($this->inventory->id, 20);

        expect($this->product->stock)->toBe(20)
            ->and($this->product->inventoryHistories()->count())->toBe(1);
    });

    it('sets stock to new quantity', function (): void {
        $this->product->mutateStock($this->inventory->id, 10);

        $history = $this->product->setStock(25, $this->inventory->id, oldQuantity: 10);

        expect($history)->toBeInstanceOf(InventoryHistory::class)
            ->and($history->quantity)->toBe(15)
            ->and($this->product->stock)->toBe(25);
    });

    it('sets stock returns null when quantity is same', function (): void {
        $this->product->mutateStock($this->inventory->id, 10);

        $history = $this->product->setStock(10, $this->inventory->id, oldQuantity: 10);

        expect($history)->toBeNull();
    });

    it('creates stock mutation with user', function (): void {
        $user = User::factory()->create();
        $this->actingAs($user);

        $history = $this->product->mutateStock(
            inventoryId: $this->inventory->id,
            quantity: 10,
            event: 'purchase',
            description: 'Initial stock',
        );

        expect($history->description)->toBe('Initial stock')
            ->and($history->event)->toBe('purchase')
            ->and($history->old_quantity)->toBe(0)
            ->and($history->user_id)->toBe($user->id);
    });

    it('creates stock mutation with reference', function (): void {
        $reference = Product::factory()->create();

        $history = $this->product->mutateStock(
            inventoryId: $this->inventory->id,
            quantity: 10,
            reference: $reference,
        );

        expect($history->reference_type)->toBe($reference->getMorphClass())
            ->and($history->reference_id)->toBe($reference->id);
    });

    it('has inventory histories relationship', function (): void {
        $this->product->mutateStock($this->inventory->id, 10);
        $this->product->mutateStock($this->inventory->id, 5, oldQuantity: 10);

        expect($this->product->inventoryHistories())->toBeInstanceOf(MorphMany::class)
            ->and($this->product->inventoryHistories()->count())->toBe(2);
    });

    it('checks product type methods', function (): void {
        $standard = Product::factory()->create(['type' => ProductType::Standard]);
        $variant = Product::factory()->create(['type' => ProductType::Variant]);
        $external = Product::factory()->create(['type' => ProductType::External]);
        $virtual = Product::factory()->create(['type' => ProductType::Virtual]);

        expect($standard->isStandard())->toBeTrue()
            ->and($standard->isVariant())->toBeFalse()
            ->and($variant->isVariant())->toBeTrue()
            ->and($variant->isStandard())->toBeFalse()
            ->and($external->isExternal())->toBeTrue()
            ->and($virtual->isVirtual())->toBeTrue();
    });

    it('checks product capabilities based on type', function (): void {
        $standard = Product::factory()->create(['type' => ProductType::Standard]);
        $variant = Product::factory()->create(['type' => ProductType::Variant]);
        $virtual = Product::factory()->create(['type' => ProductType::Virtual]);

        expect($standard->canUseShipping())->toBeTrue()
            ->and($standard->canUseAttributes())->toBeTrue()
            ->and($standard->canUseVariants())->toBeFalse()
            ->and($variant->canUseVariants())->toBeTrue()
            ->and($virtual->canUseShipping())->toBeFalse();
    });

    it('has brand relationship', function (): void {
        $brand = Brand::factory()->create();
        $product = Product::factory()->create(['brand_id' => $brand->id]);

        expect($product->brand)->toBeInstanceOf(Brand::class)
            ->and($product->brand->id)->toBe($brand->id);
    });

    it('has categories relationship', function (): void {
        $product = Product::factory()->create();
        $categories = Category::factory()->count(3)->create();
        $product->categories()->attach($categories);

        expect($product->categories()->count())->toBe(3);
    });

    it('has channels relationship', function (): void {
        $product = Product::factory()->create();
        $channels = Channel::factory()->count(2)->create();
        $product->channels()->attach($channels);

        expect($product->channels()->count())->toBe(2);
    });

    it('has discounts relationship from HasDiscounts trait', function (): void {
        $product = Product::factory()->create();
        $discount = Discount::factory()->create();

        DiscountDetail::factory()->create([
            'discount_id' => $discount->id,
            'discountable_id' => $product->id,
            'discountable_type' => $product->getMorphClass(),
        ]);

        expect($product->discounts())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\MorphToMany::class)
            ->and($product->discounts()->count())->toBeGreaterThan(0);
    });

    it('has ratings relationship from InteractsWithReviews trait', function (): void {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $user->id,
            'author_type' => $user->getMorphClass(),
            'rating' => 5,
            'title' => 'Great',
            'content' => 'Great product',
            'approved' => true,
        ]);

        expect($product->ratings())->toBeInstanceOf(MorphMany::class)
            ->and($product->ratings()->count())->toBe(1);
    });

    it('calculates average rating from InteractsWithReviews trait', function (): void {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $user->id,
            'author_type' => $user->getMorphClass(),
            'rating' => 4,
            'approved' => true,
        ]);

        Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $user->id,
            'author_type' => $user->getMorphClass(),
            'rating' => 5,
            'approved' => true,
        ]);

        $average = $product->averageRating(2, true);
        expect($average)->toBeInstanceOf(Illuminate\Support\Collection::class);
    });

    it('counts ratings from InteractsWithReviews trait', function (): void {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        Review::factory()->create([
            'reviewrateable_id' => $product->id,
            'reviewrateable_type' => $product->getMorphClass(),
            'author_id' => $user->id,
            'author_type' => $user->getMorphClass(),
            'rating' => 5,
            'approved' => true,
        ]);

        $product->refresh();

        expect($product->countRating())->toBe(1);
    });

    it('has variants relationship', function (): void {
        $product = Product::factory()->create(['type' => ProductType::Variant]);
        ProductVariant::factory()->create(['product_id' => $product->id]);

        expect($product->variants())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class)
            ->and($product->variants()->count())->toBe(1);
    });

    it('calculates variants stock accessor', function (): void {
        $product = Product::factory()->create(['type' => ProductType::Variant]);
        $variant1 = ProductVariant::factory()->create(['product_id' => $product->id]);
        $variant2 = ProductVariant::factory()->create(['product_id' => $product->id]);

        $variant1->mutateStock($this->inventory->id, 10);
        $variant2->mutateStock($this->inventory->id, 5);

        expect($product->fresh()->variants_stock)->toBe(15);
    });

    it('has collections relationship', function (): void {
        $product = Product::factory()->create();
        $collections = Collection::factory()->count(2)->create();
        $product->collections()->attach($collections);

        expect($product->collections()->count())->toBe(2);
    });

    it('has related products relationship', function (): void {
        $product = Product::factory()->create();
        $relatedProducts = Product::factory()->count(3)->create();
        $product->relatedProducts()->attach($relatedProducts);

        expect($product->relatedProducts()->count())->toBe(3);
    });

    it('has options relationship', function (): void {
        $product = Product::factory()->create();
        $attribute = Shopper\Core\Models\Attribute::factory()->create();
        $product->options()->attach($attribute);

        expect($product->options()->count())->toBe(1);
    });

    it('filters products by publish scope', function (): void {
        $publishedProduct = Product::factory()->create([
            'published_at' => now()->subDay(),
            'is_visible' => true,
        ]);
        Product::factory()->create([
            'published_at' => now()->addDay(),
            'is_visible' => true,
        ]);
        Product::factory()->create([
            'published_at' => now()->subDay(),
            'is_visible' => false,
        ]);

        $results = Product::publish()->get();

        expect($results->count())->toBe(1)
            ->and($results->first()->id)->toBe($publishedProduct->id);
    });

    it('filters products by channel scope', function (): void {
        $channel1 = Channel::factory()->create();
        $channel2 = Channel::factory()->create();

        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        $product3 = Product::factory()->create();

        $product1->channels()->attach($channel1);
        $product2->channels()->attach($channel2);
        $product3->channels()->attach([$channel1->id, $channel2->id]);

        $resultsChannel1 = Product::forChannel($channel1->id)->get();
        $resultsChannel2 = Product::forChannel($channel2->id)->get();

        expect($resultsChannel1->count())->toBe(2)
            ->and($resultsChannel2->count())->toBe(2);
    });

    it('casts featured to boolean', function (): void {
        $product = Product::factory()->create(['featured' => 1]);

        expect($product->featured)->toBeTrue()
            ->and($product->featured)->toBeBool();
    });

    it('casts is_visible to boolean', function (): void {
        $product = Product::factory()->create(['is_visible' => 1]);

        expect($product->is_visible)->toBeTrue()
            ->and($product->is_visible)->toBeBool();
    });

    it('casts published_at to datetime', function (): void {
        $publishedAt = now();
        $product = Product::factory()->create(['published_at' => $publishedAt]);

        expect($product->published_at)->toBeInstanceOf(Carbon::class);
    });

    it('casts metadata to array', function (): void {
        $metadata = ['key' => 'value', 'another_key' => 'another_value'];
        $product = Product::factory()->create(['metadata' => $metadata]);

        expect($product->metadata)->toBeArray()
            ->and($product->metadata)->toBe($metadata);
    });

    it('can register media collections from HasMedia trait', function (): void {
        $product = Product::factory()->create();

        $product->registerMediaCollections();

        expect($product->getMediaCollection(config('shopper.media.storage.collection_name')))->not->toBeNull()
            ->and($product->getMediaCollection(config('shopper.media.storage.thumbnail_collection')))->not->toBeNull()
            ->and($product->getMediaCollection('files'))->not->toBeNull();
    });

    it('implements SpatieHasMedia interface from HasMedia trait', function (): void {
        $product = Product::factory()->create();

        expect($product)->toBeInstanceOf(Spatie\MediaLibrary\HasMedia::class);
    });

    it('deletes prices when product is deleted', function (): void {
        $product = Product::factory()->create();
        $currency = Currency::query()->first();

        Price::factory()->create([
            'priceable_id' => $product->id,
            'priceable_type' => $product->getMorphClass(),
            'currency_id' => $currency->id,
        ]);

        expect($product->prices()->count())->toBe(1);

        $product->delete();

        expect(Price::query()->where('priceable_id', $product->id)->count())->toBe(0);
    });

    it('clears stock when product is deleted', function (): void {
        $inventory = Inventory::factory()->create();
        $product = Product::factory()->create();

        $product->mutateStock($inventory->id, 10);

        expect($product->stock)->toBe(10)
            ->and($product->inventoryHistories()->count())->toBe(1);

        $product->delete();

        expect(InventoryHistory::query()
            ->where('stockable_id', $product->id)
            ->where('stockable_type', $product->getMorphClass())
            ->count())->toBe(0);
    });
})->group('product', 'models');
