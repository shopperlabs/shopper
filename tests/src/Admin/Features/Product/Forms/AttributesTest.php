<?php

declare(strict_types=1);

use Shopper\Core\Enum\FieldType;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeValue;
use Shopper\Core\Models\Product;
use Shopper\Livewire\SlideOvers\ChooseProductAttributes;
use Shopper\Tests\Admin\Features\TestCase;

use function Pest\Livewire\livewire;

uses(TestCase::class);

beforeEach(function (): void {
    $this->product = Product::factory()->variant()->create();
    $this->colorAttribute = Attribute::factory()
        ->has(AttributeValue::factory()->count(3), 'values')
        ->create([
            'name' => 'Color',
            'slug' => 'color',
            'type' => FieldType::ColorPicker,
            'is_enabled' => true,
        ]);
    $this->sizeAttribute = Attribute::factory()
        ->has(AttributeValue::factory()->count(5), 'values')
        ->create([
            'name' => 'Size',
            'slug' => 'size',
            'type' => FieldType::Checkbox,
            'is_enabled' => true,
        ]);
    $this->dimensionAttribute = Attribute::factory()
        ->has(AttributeValue::factory()->count(10), 'values')
        ->create([
            'name' => 'Dimension',
            'slug' => 'dimension',
            'type' => FieldType::Checkbox,
            'is_enabled' => true,
        ]);
});

it('product can choose attributes', function (): void {
    livewire(ChooseProductAttributes::class, ['productId' => $this->product->id])
        ->fillForm([
            'attributes' => [$this->colorAttribute->id],
            'values' => [
                $this->colorAttribute->id => $this->colorAttribute->values
                    ->take(2)
                    ->pluck('id')
                    ->toArray(),
                $this->sizeAttribute->id => $this->sizeAttribute->values
                    ->take(4)
                    ->pluck('id')
                    ->toArray(),
            ],
        ])
        ->call('store')
        ->assertHasNoErrors()
        ->assertRedirectToRoute('shopper.products.edit', ['product' => $this->product->id, 'tab' => 'attributes']);

    expect($this->product->options->count())
        ->toBe(2);
})->group('product');
