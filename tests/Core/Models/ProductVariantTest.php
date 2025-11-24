<?php

declare(strict_types=1);

use Shopper\Core\Enum\Dimension\Length;
use Shopper\Core\Enum\Dimension\Weight;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\InventoryHistory;
use Shopper\Core\Models\Price;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductVariant;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe(ProductVariant::class, function (): void {
    it('belongs to product', function (): void {
        $product = Product::factory()->create(['type' => ProductType::Variant]);
        $variant = ProductVariant::factory()->create(['product_id' => $product->id]);

        expect($variant->product->id)->toBe($product->id);
    });

    it('has dimensions', function (): void {
        $variant = ProductVariant::factory()->create([
            'weight_unit' => Weight::KG,
            'weight_value' => 2.5,
            'height_unit' => Length::CM,
            'height_value' => 10.5,
            'width_unit' => Length::CM,
            'width_value' => 20.0,
            'depth_unit' => Length::CM,
            'depth_value' => 15.0,
        ]);

        expect($variant->weight_unit)->toBe(Weight::KG)
            ->and($variant->weight_value)->toBe('2.50')
            ->and($variant->height_unit)->toBe(Length::CM)
            ->and($variant->height_value)->toBe('10.50');
    });

    it('has backorder flag', function (): void {
        $variant = ProductVariant::factory()->create(['allow_backorder' => true]);

        expect($variant->allow_backorder)->toBeTrue();
    });

    it('has values relationship', function (): void {
        $variant = ProductVariant::factory()->create();

        expect($variant->values())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
    });

    it('deletes prices when variant is deleted', function (): void {
        $variant = ProductVariant::factory()->create();
        $currency = Currency::query()->first();

        Price::factory()->create([
            'priceable_id' => $variant->id,
            'priceable_type' => $variant->getMorphClass(),
            'currency_id' => $currency->id,
        ]);

        expect($variant->prices()->count())->toBe(1);

        $variant->delete();

        expect(Price::query()->where('priceable_id', $variant->id)->count())->toBe(0);
    });

    it('clears stock when variant is deleted', function (): void {
        $inventory = Inventory::factory()->create();
        $variant = ProductVariant::factory()->create();

        $variant->mutateStock($inventory->id, 10, ['old_quantity' => 0]);

        expect($variant->stock)->toBe(10)
            ->and($variant->inventoryHistories()->count())->toBe(1);

        $variant->delete();

        expect(InventoryHistory::query()
            ->where('stockable_id', $variant->id)
            ->where('stockable_type', $variant->getMorphClass())
            ->count())->toBe(0);
    });
})->group('product', 'models');
