<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\FieldType;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeValue;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\User;
use Shopper\Livewire\SlideOvers\ChooseProductAttributes;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->product = Product::factory()->create();
});

describe(ChooseProductAttributes::class, function (): void {
    it('can render choose product attributes slideover', function (): void {
        Livewire::test(ChooseProductAttributes::class, ['productId' => $this->product->id])
            ->assertOk();
    });

    it('initializes with data containing attributes array', function (): void {
        $component = Livewire::test(ChooseProductAttributes::class, ['productId' => $this->product->id]);

        expect($component->get('data'))->toBeArray()
            ->and($component->get('data'))->toHaveKey('attributes');
    });

    it('has correct product id', function (): void {
        $component = Livewire::test(ChooseProductAttributes::class, ['productId' => $this->product->id]);

        expect($component->get('productId'))->toBe($this->product->id);
    });

    it('redirects to product edit page after storing', function (): void {
        $attribute = Attribute::factory()->create([
            'is_enabled' => true,
            'type' => FieldType::Select,
        ]);
        $value = AttributeValue::factory()->create(['attribute_id' => $attribute->id]);

        Livewire::test(ChooseProductAttributes::class, ['productId' => $this->product->id])
            ->set('data', [
                'attributes' => [$attribute->id],
                'values' => [
                    $attribute->id => $value->id,
                ],
            ])
            ->call('store')
            ->assertRedirect(route('shopper.products.edit', [
                'product' => $this->product->id,
                'tab' => 'attributes',
            ]));
    });

    it('sends notification after storing attributes', function (): void {
        $attribute = Attribute::factory()->create([
            'is_enabled' => true,
            'type' => FieldType::Select,
        ]);
        $value = AttributeValue::factory()->create(['attribute_id' => $attribute->id]);

        Livewire::test(ChooseProductAttributes::class, ['productId' => $this->product->id])
            ->set('data', [
                'attributes' => [$attribute->id],
                'values' => [
                    $attribute->id => $value->id,
                ],
            ])
            ->call('store')
            ->assertNotified();
    });
})->group('livewire', 'slideovers', 'products');
