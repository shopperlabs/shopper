<?php

declare(strict_types=1);

use Shopper\Core\Enum\Operator;
use Shopper\Core\Enum\Rule;
use Shopper\Core\Models\Collection;
use Shopper\Core\Models\CollectionRule;

uses(Tests\Core\TestCase::class);

describe(CollectionRule::class, function (): void {
    it('belongs to collection', function (): void {
        /** @var Collection $collection */
        $collection = Collection::factory()->create(['slug' => 'test-collection']);
        $rule = CollectionRule::factory()->create([
            'collection_id' => $collection->id,
            'rule' => Rule::ProductTitle,
            'operator' => Operator::Contains,
            'value' => 'Test',
        ]);

        expect($rule->collection->id)->toBe($collection->id);
    });

    it('has formatted rule', function (): void {
        /** @var Collection $collection */
        $collection = Collection::factory()->create(['slug' => 'test-collection']);
        $rule = CollectionRule::factory()->create([
            'collection_id' => $collection->id,
            'rule' => Rule::ProductTitle,
            'operator' => Operator::Contains,
            'value' => 'Test',
        ]);

        expect($rule->getFormattedRule())->toBeString();
    });

    it('has formatted operator', function (): void {
        /** @var Collection $collection */
        $collection = Collection::factory()->create(['slug' => 'test-collection']);
        $rule = CollectionRule::factory()->create([
            'collection_id' => $collection->id,
            'rule' => Rule::ProductTitle,
            'operator' => Operator::Contains,
            'value' => 'Test',
        ]);

        expect($rule->getFormattedOperator())->toBeString();
    });

    it('formats value for product price rule', function (): void {
        /** @var Collection $collection */
        $collection = Collection::factory()->create(['slug' => 'test-collection']);
        $rule = CollectionRule::factory()->create([
            'collection_id' => $collection->id,
            'rule' => Rule::ProductPrice,
            'operator' => Operator::GreaterThan,
            'value' => '1000',
        ]);

        $formatted = $rule->getFormattedValue();

        expect($formatted)->toBeString()
            ->and($formatted)->toContain('$');
    });

    it('returns raw value for non-price rules', function (): void {
        /** @var Collection $collection */
        $collection = Collection::factory()->create(['slug' => 'test-collection']);
        $rule = CollectionRule::factory()->create([
            'collection_id' => $collection->id,
            'rule' => Rule::ProductTitle,
            'operator' => Operator::Contains,
            'value' => 'Test Product',
        ]);

        expect($rule->getFormattedValue())->toBe('Test Product');
    });

    it('casts rule to Rule enum', function (): void {
        /** @var Collection $collection */
        $collection = Collection::factory()->create(['slug' => 'test-collection']);
        $rule = CollectionRule::factory()->create([
            'collection_id' => $collection->id,
            'rule' => Rule::ProductTitle,
            'operator' => Operator::Contains,
            'value' => 'Test',
        ]);

        expect($rule->rule)->toBeInstanceOf(Rule::class)
            ->and($rule->rule)->toBe(Rule::ProductTitle);
    });

    it('casts operator to Operator enum', function (): void {
        /** @var Collection $collection */
        $collection = Collection::factory()->create(['slug' => 'test-collection']);
        $rule = CollectionRule::factory()->create([
            'collection_id' => $collection->id,
            'rule' => Rule::ProductTitle,
            'operator' => Operator::Contains,
            'value' => 'Test',
        ]);

        expect($rule->operator)->toBeInstanceOf(Operator::class)
            ->and($rule->operator)->toBe(Operator::Contains);
    });
})->group('collection', 'models');
