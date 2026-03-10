<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeValue;

uses(Tests\Core\TestCase::class);

describe(AttributeValue::class, function (): void {
    it('belongs to attribute', function (): void {
        /** @var Attribute $attribute */
        $attribute = Attribute::factory()->create();
        $value = AttributeValue::factory()->create(['attribute_id' => $attribute->id]);

        expect($value->attribute->id)->toBe($attribute->id);
    });

    it('has key and value properties', function (): void {
        /** @var Attribute $attribute */
        $attribute = Attribute::factory()->create();
        $value = AttributeValue::factory()->create([
            'attribute_id' => $attribute->id,
            'key' => 'size-medium',
            'value' => 'Medium',
        ]);

        expect($value->key)->toBe('size-medium')
            ->and($value->value)->toBe('Medium');
    });

    it('has variants relationship', function (): void {
        $attribute = Attribute::factory()->create();
        $value = AttributeValue::factory()->create(['attribute_id' => $attribute->id]);

        expect($value->variants())->toBeInstanceOf(BelongsToMany::class);
    });
})->group('attribute', 'models');
