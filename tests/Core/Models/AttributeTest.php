<?php

declare(strict_types=1);

use Shopper\Core\Enum\FieldType;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeValue;
use Shopper\Core\Models\Product;

uses(Tests\TestCase::class);

describe(Attribute::class, function (): void {
    it('has correct field types', function (): void {
        $text = Attribute::factory()->create(['type' => FieldType::Text]);
        $number = Attribute::factory()->create(['type' => FieldType::Number]);
        $richtext = Attribute::factory()->create(['type' => FieldType::RichText]);

        expect($text->type)->toBe(FieldType::Text)
            ->and($number->type)->toBe(FieldType::Number)
            ->and($richtext->type)->toBe(FieldType::RichText);
    });

    it('checks if searchable', function (): void {
        $searchable = Attribute::factory()->create(['is_searchable' => true]);
        $notSearchable = Attribute::factory()->create(['is_searchable' => false]);

        expect($searchable->is_searchable)->toBeTrue()
            ->and($notSearchable->is_searchable)->toBeFalse();
    });

    it('checks if filterable', function (): void {
        $filterable = Attribute::factory()->create(['is_filterable' => true]);
        $notFilterable = Attribute::factory()->create(['is_filterable' => false]);

        expect($filterable->is_filterable)->toBeTrue()
            ->and($notFilterable->is_filterable)->toBeFalse();
    });

    it('has values relationship', function (): void {
        $attribute = Attribute::factory()->create();
        AttributeValue::factory()->count(5)->create(['attribute_id' => $attribute->id]);

        expect($attribute->values()->count())->toBe(5);
    });

    it('returns types fields', function (): void {
        $types = Attribute::typesFields();

        expect($types)->toBeArray()
            ->and($types)->not->toBeEmpty();
    });

    it('returns fields with values', function (): void {
        $fields = Attribute::fieldsWithValues();

        expect($fields)->toBeArray()
            ->and($fields)->toContain(FieldType::Checkbox)
            ->and($fields)->toContain(FieldType::ColorPicker)
            ->and($fields)->toContain(FieldType::Select);
    });

    it('checks if has multiple values', function (): void {
        $checkbox = Attribute::factory()->create(['type' => FieldType::Checkbox]);
        $colorPicker = Attribute::factory()->create(['type' => FieldType::ColorPicker]);
        $text = Attribute::factory()->create(['type' => FieldType::Text]);

        expect($checkbox->hasMultipleValues())->toBeTrue()
            ->and($colorPicker->hasMultipleValues())->toBeTrue()
            ->and($text->hasMultipleValues())->toBeFalse();
    });

    it('checks if has single value', function (): void {
        $select = Attribute::factory()->create(['type' => FieldType::Select]);
        $text = Attribute::factory()->create(['type' => FieldType::Text]);

        expect($select->hasSingleValue())->toBeTrue()
            ->and($text->hasSingleValue())->toBeFalse();
    });

    it('checks if has text value', function (): void {
        $text = Attribute::factory()->create(['type' => FieldType::Text]);
        $number = Attribute::factory()->create(['type' => FieldType::Number]);
        $richtext = Attribute::factory()->create(['type' => FieldType::RichText]);
        $datepicker = Attribute::factory()->create(['type' => FieldType::DatePicker]);
        $select = Attribute::factory()->create(['type' => FieldType::Select]);

        expect($text->hasTextValue())->toBeTrue()
            ->and($number->hasTextValue())->toBeTrue()
            ->and($richtext->hasTextValue())->toBeTrue()
            ->and($datepicker->hasTextValue())->toBeTrue()
            ->and($select->hasTextValue())->toBeFalse();
    });

    it('can update status', function (): void {
        $attribute = Attribute::factory()->create(['is_enabled' => false]);

        $attribute->updateStatus();

        expect($attribute->fresh()->is_enabled)->toBeTrue();
    });

    it('has enabled scope', function (): void {
        Attribute::factory()->create(['is_enabled' => false]);
        $enabled = Attribute::factory()->create(['is_enabled' => true]);

        $result = Attribute::enabled()->where('id', $enabled->id)->first();

        expect($result->id)->toBe($enabled->id)
            ->and($result->is_enabled)->toBeTrue();
    });

    it('has isFilterable scope', function (): void {
        Attribute::factory()->create(['is_filterable' => false]);
        $filterable = Attribute::factory()->create(['is_filterable' => true]);

        $result = Attribute::isFilterable()->where('id', $filterable->id)->first();

        expect($result->id)->toBe($filterable->id)
            ->and($result->is_filterable)->toBeTrue();
    });

    it('has isSearchable scope', function (): void {
        Attribute::factory()->create(['is_searchable' => false]);
        $searchable = Attribute::factory()->create(['is_searchable' => true]);

        $result = Attribute::isSearchable()->where('id', $searchable->id)->first();

        expect($result->id)->toBe($searchable->id)
            ->and($result->is_searchable)->toBeTrue();
    });

    it('has products relationship', function (): void {
        $attribute = Attribute::factory()->create();
        $product = Product::factory()->create();

        $attribute->products()->attach($product, [
            'attribute_value_id' => null,
            'attribute_custom_value' => 'Test Value',
        ]);

        expect($attribute->products()->count())->toBe(1);
    });

    it('has type formatted accessor', function (): void {
        $attribute = Attribute::factory()->create(['type' => FieldType::Text]);

        expect($attribute->type_formatted)->toBeString()
            ->and($attribute->type_formatted)->not->toBeEmpty();
    });

    it('casts type to FieldType enum', function (): void {
        $attribute = Attribute::factory()->create(['type' => FieldType::Text]);

        expect($attribute->type)->toBeInstanceOf(FieldType::class)
            ->and($attribute->type)->toBe(FieldType::Text);
    });
})->group('attribute', 'models');
