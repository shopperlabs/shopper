<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\FieldType;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeValue;
use Shopper\Livewire\SlideOvers\AttributeValues;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->attribute = Attribute::factory()->create([
        'name' => 'Color',
        'type' => FieldType::Text,
    ]);
});

describe(AttributeValues::class, function (): void {
    it('can render attribute values slideover', function (): void {
        Livewire::test(AttributeValues::class, ['attributeId' => $this->attribute->id])
            ->assertOk();
    });

    it('loads attribute with values on mount', function (): void {
        AttributeValue::factory()->count(3)->create(['attribute_id' => $this->attribute->id]);

        $component = Livewire::test(AttributeValues::class, ['attributeId' => $this->attribute->id]);

        expect($component->get('attribute'))->not->toBeNull()
            ->and($component->get('attribute')->id)->toBe($this->attribute->id)
            ->and($component->get('values')->count())->toBe(3);
    });

    it('displays attribute values in table', function (): void {
        AttributeValue::factory()->create([
            'attribute_id' => $this->attribute->id,
            'key' => 'red',
            'value' => 'Red',
        ]);
        AttributeValue::factory()->create([
            'attribute_id' => $this->attribute->id,
            'key' => 'blue',
            'value' => 'Blue',
        ]);

        Livewire::test(AttributeValues::class, ['attributeId' => $this->attribute->id])
            ->assertCanSeeTableRecords(
                AttributeValue::query()->where('attribute_id', $this->attribute->id)->get()
            );
    });

    it('can create new attribute value via header action', function (): void {
        Livewire::test(AttributeValues::class, ['attributeId' => $this->attribute->id])
            ->callTableAction('add', data: [
                'key' => 'GREEN',
                'value' => 'Green',
            ])
            ->assertHasNoTableActionErrors();

        $value = AttributeValue::query()->where('attribute_id', $this->attribute->id)->first();

        expect($value)->not->toBeNull()
            ->and($value->key)->toBe('green')
            ->and($value->value)->toBe('Green');
    });

    it('converts key to lowercase when creating value', function (): void {
        Livewire::test(AttributeValues::class, ['attributeId' => $this->attribute->id])
            ->callTableAction('add', data: [
                'key' => 'UPPERCASE_KEY',
                'value' => 'Test Value',
            ])
            ->assertHasNoTableActionErrors();

        $value = AttributeValue::query()->where('attribute_id', $this->attribute->id)->first();

        expect($value->key)->toBe('uppercase_key');
    });

    it('validates required fields when creating value', function (): void {
        Livewire::test(AttributeValues::class, ['attributeId' => $this->attribute->id])
            ->callTableAction('add', data: [
                'key' => '',
                'value' => '',
            ])
            ->assertHasTableActionErrors([
                'key' => 'required',
                'value' => 'required',
            ]);
    });

    it('validates unique key when creating value', function (): void {
        AttributeValue::factory()->create([
            'attribute_id' => $this->attribute->id,
            'key' => 'existing',
            'value' => 'Existing Value',
        ]);

        Livewire::test(AttributeValues::class, ['attributeId' => $this->attribute->id])
            ->callTableAction('add', data: [
                'key' => 'existing',
                'value' => 'New Value',
            ])
            ->assertHasTableActionErrors(['key' => 'unique']);
    });

    it('can edit attribute value', function (): void {
        $value = AttributeValue::factory()->create([
            'attribute_id' => $this->attribute->id,
            'key' => 'old',
            'value' => 'Old Value',
        ]);

        Livewire::test(AttributeValues::class, ['attributeId' => $this->attribute->id])
            ->callTableAction('edit', $value, data: [
                'key' => 'NEW',
                'value' => 'New Value',
            ])
            ->assertHasNoTableActionErrors();

        $value->refresh();

        expect($value->key)->toBe('new')
            ->and($value->value)->toBe('New Value');
    });

    it('can delete attribute value via action', function (): void {
        $value = AttributeValue::factory()->create([
            'attribute_id' => $this->attribute->id,
        ]);

        Livewire::test(AttributeValues::class, ['attributeId' => $this->attribute->id])
            ->callTableAction('delete', $value);

        expect(AttributeValue::query()->find($value->id))->toBeNull();
    });

    it('can bulk delete attribute values', function (): void {
        $values = AttributeValue::factory()->count(3)->create([
            'attribute_id' => $this->attribute->id,
        ]);

        Livewire::test(AttributeValues::class, ['attributeId' => $this->attribute->id])
            ->callTableBulkAction('delete', $values);

        expect(AttributeValue::query()->whereIn('id', $values->pluck('id'))->count())->toBe(0);
    });

    it('can remove value via method', function (): void {
        $value = AttributeValue::factory()->create([
            'attribute_id' => $this->attribute->id,
        ]);

        Livewire::test(AttributeValues::class, ['attributeId' => $this->attribute->id])
            ->call('removeValue', $value->id)
            ->assertDispatched('updateValues');

        expect(AttributeValue::query()->find($value->id))->toBeNull();
    });

    it('updates values list when updateValues event is dispatched', function (): void {
        $component = Livewire::test(AttributeValues::class, ['attributeId' => $this->attribute->id]);

        expect($component->get('values')->count())->toBe(0);

        AttributeValue::factory()->count(2)->create(['attribute_id' => $this->attribute->id]);

        $component->dispatch('updateValues');

        expect($component->get('values')->count())->toBe(2);
    });

    it('shows color column for color picker attribute type', function (): void {
        $colorAttribute = Attribute::factory()->create([
            'type' => FieldType::ColorPicker,
        ]);
        AttributeValue::factory()->create([
            'attribute_id' => $colorAttribute->id,
            'key' => '#ff0000',
            'value' => 'Red',
        ]);

        Livewire::test(AttributeValues::class, ['attributeId' => $colorAttribute->id])
            ->assertCanSeeTableRecords(
                AttributeValue::query()->where('attribute_id', $colorAttribute->id)->get()
            );
    });

    it('only shows values for the specific attribute', function (): void {
        $otherAttribute = Attribute::factory()->create();

        AttributeValue::factory()->create(['attribute_id' => $this->attribute->id, 'key' => 'attr1']);
        AttributeValue::factory()->create(['attribute_id' => $otherAttribute->id, 'key' => 'attr2']);

        $component = Livewire::test(AttributeValues::class, ['attributeId' => $this->attribute->id]);

        expect($component->get('values')->count())->toBe(1)
            ->and($component->get('values')->first()->key)->toBe('attr1');
    });
})->group('livewire', 'slideovers', 'attributes');
