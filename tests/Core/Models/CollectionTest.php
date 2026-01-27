<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Enum\CollectionType;
use Shopper\Core\Enum\Operator;
use Shopper\Core\Enum\Rule;
use Shopper\Core\Models\Collection;
use Shopper\Core\Models\CollectionRule;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\Zone;

uses(Tests\TestCase::class);

describe(Collection::class, function (): void {
    it('has correct type methods', function (): void {
        $auto = Collection::factory()->create(['type' => CollectionType::Auto]);
        $manual = Collection::factory()->create(['type' => CollectionType::Manual]);

        expect($auto->isAutomatic())->toBeTrue()
            ->and($auto->isManual())->toBeFalse()
            ->and($manual->isManual())->toBeTrue()
            ->and($manual->isAutomatic())->toBeFalse();
    });

    it('has products relationship', function (): void {
        $collection = Collection::factory()->create();
        $products = Product::factory()->count(3)->create();

        foreach ($products as $product) {
            $product->collections()->attach($collection);
        }

        expect($collection->products()->count())->toBe(3);
    });

    it('has rules relationship', function (): void {
        $collection = Collection::factory()->create();

        expect($collection->rules())->toBeInstanceOf(HasMany::class);
    });

    it('returns first rule for automatic collection', function (): void {
        /** @var Collection $collection */
        $collection = Collection::factory()->create(['type' => CollectionType::Auto]);

        CollectionRule::factory()->create([
            'collection_id' => $collection->id,
            'rule' => Rule::ProductTitle,
            'operator' => Operator::Contains,
            'value' => 'test',
        ]);

        CollectionRule::factory()->create([
            'collection_id' => $collection->id,
            'rule' => Rule::ProductPrice,
            'operator' => Operator::EqualsTo,
            'value' => '100',
        ]);

        $firstRule = $collection->firstRule();

        expect($firstRule)->toBeString()
            ->and($firstRule)->toContain('other');
    });

    it('returns null for first rule of manual collection', function (): void {
        /** @var Collection $collection */
        $collection = Collection::factory()->create(['type' => CollectionType::Manual]);

        expect($collection->firstRule())->toBeNull();
    });

    it('has manual scope', function (): void {
        Collection::factory()->create(['type' => CollectionType::Auto]);
        /** @var Collection $manual */
        $manual = Collection::factory()->create(['type' => CollectionType::Manual]);

        $result = Collection::manual()->where('id', $manual->id)->first();

        expect($result->id)->toBe($manual->id)
            ->and($result->type)->toBe(CollectionType::Manual);
    });

    it('has automatic scope', function (): void {
        Collection::factory()->create(['type' => CollectionType::Manual]);
        $auto = Collection::factory()->create(['type' => CollectionType::Auto]);

        $result = Collection::automatic()->where('id', $auto->id)->first();

        expect($result->id)->toBe($auto->id)
            ->and($result->type)->toBe(CollectionType::Auto);
    });

    it('casts type to CollectionType enum', function (): void {
        $collection = Collection::factory()->create(['type' => CollectionType::Auto]);

        expect($collection->type)->toBeInstanceOf(CollectionType::class)
            ->and($collection->type)->toBe(CollectionType::Auto);
    });

    it('casts metadata to array', function (): void {
        $metadata = ['key' => 'value', 'another_key' => 'another_value'];
        $collection = Collection::factory()->create(['metadata' => $metadata]);

        expect($collection->metadata)->toBeArray()
            ->and($collection->metadata)->toBe($metadata);
    });

    it('has zones relationship', function (): void {
        $collection = Collection::factory()->create();

        expect($collection->zones())->toBeInstanceOf(MorphToMany::class);
    });

    it('can attach zones to a collection', function (): void {
        $collection = Collection::factory()->create();
        $zones = Zone::factory()->count(2)->create();

        $collection->zones()->attach($zones);

        expect($collection->zones)->toHaveCount(2);
    });

    it('can detach zones from a collection', function (): void {
        $collection = Collection::factory()->create();
        $zone = Zone::factory()->create();

        $collection->zones()->attach($zone);

        expect($collection->zones)->toHaveCount(1);

        $collection->zones()->detach($zone);

        expect($collection->refresh()->zones)->toHaveCount(0);
    });

    it('can register media collections from HasMedia trait', function (): void {
        $collection = Collection::factory()->create();

        $collection->registerMediaCollections();

        expect($collection->getMediaCollection(config('shopper.media.storage.collection_name')))->not->toBeNull();
    });

    it('implements SpatieHasMedia interface from HasMedia trait', function (): void {
        $collection = Collection::factory()->create();

        expect($collection)->toBeInstanceOf(Spatie\MediaLibrary\HasMedia::class);
    });
})->group('collection', 'models');
