<?php

declare(strict_types=1);

use Filament\Actions\Testing\TestAction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Livewire;
use Shopper\Core\Enum\FieldType;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeProduct;
use Shopper\Core\Models\AttributeValue;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Inventory;
use Shopper\Livewire\Components\Products\VariantStock;
use Shopper\Livewire\Pages\Product\Attributes;
use Shopper\Livewire\SlideOvers\ChooseProductAttributes;
use Shopper\Livewire\SlideOvers\GenerateVariants;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\ProductVariant;
use Tests\Core\Stubs\RecordingEngine;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {

    setupCurrencies();

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('products.variants.edit', 'products.variants.create');
    $this->actingAs($this->user);

    $this->product = Product::factory()->create(['type' => ProductType::Variant]);
});

describe(GenerateVariants::class, function (): void {
    it('requires products.variants.delete to drop the existing variants', function (): void {
        $variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);

        Livewire::test(GenerateVariants::class, ['product' => $this->product])
            ->set('variants', [
                ['key' => 'red', 'variant_id' => null, 'name' => 'Red', 'sku' => 'NEW-RED', 'price' => 1000, 'stock' => 0, 'values' => []],
            ])
            ->call('generate');

        expect($this->product->variants()->pluck('id')->all())->toBe([$variant->id]);
    });

    it('requires products.variants.create to add variants', function (mixed $variantId): void {
        $this->user->revokePermissionTo('products.variants.create');

        Livewire::test(GenerateVariants::class, ['product' => $this->product])
            ->set('variants', [
                ['key' => 'red', 'variant_id' => $variantId, 'name' => 'Red', 'sku' => 'NEW-RED', 'price' => 1000, 'stock' => 0, 'values' => []],
            ])
            ->call('generate');

        expect($this->product->variants()->count())->toBe(0);
    })->with([null, 0, '0', false]);

    it('cannot update a variant of another product', function (): void {
        $this->withoutExceptionHandling();

        $foreign = ProductVariant::factory()->create(['product_id' => Product::factory()->create()->id]);

        expect(fn () => Livewire::test(GenerateVariants::class, ['product' => $this->product])
            ->set('variants', [
                ['key' => 'red', 'variant_id' => $foreign->id, 'name' => 'Red', 'sku' => 'RED', 'price' => 1, 'stock' => 0, 'values' => []],
            ])
            ->call('generate'))->toThrow(ModelNotFoundException::class);

        expect($foreign->prices()->where('amount', 100)->exists())->toBeFalse();
    });

    it('loads product with options and values', function (): void {
        $component = Livewire::test(GenerateVariants::class, ['product' => $this->product]);

        expect($component->get('product'))->not->toBeNull()
            ->and($component->get('product'))->toBe($this->product);
    });

    it('can remove variant from list', function (): void {
        $component = Livewire::test(GenerateVariants::class, ['product' => $this->product]);

        $component->set('variants', [
            0 => [
                'key' => 'test-key-1',
                'variant_id' => null,
                'name' => 'Variant 1',
                'sku' => 'SKU-1',
                'price' => 1000,
                'stock' => 10,
                'values' => [1, 2],
            ],
            1 => [
                'key' => 'test-key-2',
                'variant_id' => null,
                'name' => 'Variant 2',
                'sku' => 'SKU-2',
                'price' => 2000,
                'stock' => 20,
                'values' => [3, 4],
            ],
        ]);

        $component->call('removeVariant', 0);

        expect($component->get('variants'))->not->toHaveKey(0)
            ->and($component->get('variants'))->toHaveKey(1);
    });

    it('indexes the generated variant skus on the product', function (): void {
        $engine = RecordingEngine::register();
        $product = Product::factory()->publish()->create(['type' => ProductType::Variant]);

        Livewire::test(GenerateVariants::class, ['product' => $product])
            ->set('variants', [
                ['key' => 'red', 'variant_id' => null, 'name' => 'Red', 'sku' => 'AIR-RED', 'price' => 1000, 'stock' => 0, 'values' => []],
                ['key' => 'blue', 'variant_id' => null, 'name' => 'Blue', 'sku' => 'AIR-BLUE', 'price' => 1000, 'stock' => 0, 'values' => []],
            ])
            ->call('generate');

        expect($engine->documents[$product->public_id]['skus'])->toBe(['AIR-RED', 'AIR-BLUE']);
    });

    it('redirects to product variants tab after generating', function (): void {
        $component = Livewire::test(GenerateVariants::class, ['product' => $this->product]);

        $component->set('variants', [
            [
                'key' => 'test-key',
                'variant_id' => null,
                'name' => 'Test Variant',
                'sku' => 'TEST-SKU',
                'price' => 1000,
                'stock' => 10,
                'values' => [],
            ],
        ]);

        $component->call('generate')
            ->assertRedirect(route('shopper.products.edit', [
                'product' => $this->product,
                'tab' => 'variants',
            ]));
    });

    it('sends notification after generating variants', function (): void {
        $component = Livewire::test(GenerateVariants::class, ['product' => $this->product]);

        $component->set('variants', [
            [
                'key' => 'test-key',
                'variant_id' => null,
                'name' => 'Test Variant',
                'sku' => 'TEST-SKU',
                'price' => 1000,
                'stock' => 10,
                'values' => [],
            ],
        ]);

        $component->call('generate')
            ->assertNotified();
    });

    it('assigns incremental positions to generated variants', function (): void {
        $component = Livewire::test(GenerateVariants::class, ['product' => $this->product]);

        $component->set('variants', [
            [
                'key' => 'test-key-1',
                'variant_id' => null,
                'name' => 'Variant 1',
                'sku' => 'SKU-POS-1',
                'price' => 1000,
                'stock' => 10,
                'values' => [],
            ],
            [
                'key' => 'test-key-2',
                'variant_id' => null,
                'name' => 'Variant 2',
                'sku' => 'SKU-POS-2',
                'price' => 1000,
                'stock' => 10,
                'values' => [],
            ],
        ]);

        $component->call('generate');

        expect($this->product->variants()->pluck('position', 'sku')->all())
            ->toBe(['SKU-POS-1' => 1, 'SKU-POS-2' => 2]);
    });

    it('drops a compare price that is no longer above the new variant price', function (): void {
        $variantState = [
            'key' => 'test-key-1',
            'variant_id' => null,
            'name' => 'Variant 1',
            'sku' => 'SKU-CMP-1',
            'price' => '39.99',
            'stock' => 10,
            'values' => [],
        ];

        Livewire::test(GenerateVariants::class, ['product' => $this->product])
            ->set('variants', [$variantState])
            ->call('generate');

        $variant = $this->product->variants()->where('sku', 'SKU-CMP-1')->firstOrFail();
        $variant->prices()->update(['compare_amount' => 4999]);

        $variantState['variant_id'] = $variant->id;
        $variantState['price'] = '59.99';

        Livewire::test(GenerateVariants::class, ['product' => $this->product])
            ->set('variants', [$variantState])
            ->call('generate');

        $price = $variant->prices()->firstOrFail();

        expect((int) $price->amount)->toBe(5999)
            ->and($price->compare_amount)->toBeNull();
    });
    it('validates every variant row before saving any of them', function (): void {
        $foreignValue = AttributeValue::factory()->create([
            'attribute_id' => Attribute::factory()->create(['type' => FieldType::Checkbox])->id,
        ]);

        $generator = Livewire::test(GenerateVariants::class, ['product' => $this->product])
            ->set('variants', [
                ['key' => 'a', 'variant_id' => null, 'name' => '', 'sku' => 'tee', 'price' => '12,50', 'stock' => -1, 'values' => [$foreignValue->id]],
                ['key' => 'b', 'variant_id' => null, 'name' => str_repeat('a', 256), 'sku' => 'TEE', 'price' => '19.999', 'stock' => 0, 'values' => []],
            ])
            ->call('generate')
            ->assertHasErrors([
                'variants.0.name' => 'required',
                'variants.1.name' => 'max',
                'variants.0.sku' => 'distinct',
                'variants.0.price' => 'regex',
                'variants.1.price' => 'regex',
                'variants.0.stock' => 'min',
                'variants.0.values.0' => 'in',
            ])
            ->assertSee([
                __('validation.required', ['attribute' => __('shopper::forms.label.name')]),
                __('validation.distinct', ['attribute' => __('shopper::forms.label.sku')]),
                __('validation.regex', ['attribute' => __('shopper::forms.label.price')]),
                __('validation.min.numeric', ['attribute' => __('shopper::forms.label.stock_number_value'), 'min' => 0]),
                __('validation.in', ['attribute' => __('shopper::forms.label.value')]),
            ]);

        expect(mb_substr_count($generator->html(), 'fi-input-wrp fi-invalid'))->toBe(7)
            ->and(mb_substr_count($generator->html(), 'aria-invalid="true"'))->toBe(7)
            ->and(mb_substr_count($generator->html(), 'x-init="expanded = true"'))->toBe(8)
            ->and($this->product->variants()->count())->toBe(0);

        foreach (['name', 'sku', 'price', 'stock'] as $field) {
            expect($generator->html())->toContain(
                'for="variant-a-'.$field.'"',
                'id="variant-a-'.$field.'"',
                'aria-describedby="variant-a-'.$field.'-error"',
                'id="variant-a-'.$field.'-error"',
            );
        }
    });

    it('keeps the variants, their stock and their option values through the whole options workflow', function (): void {
        setupCurrencies(['USD', 'EUR'], 'USD');

        $this->user->givePermissionTo('products.edit', 'attributes.delete');
        $this->product->update(['sku' => 'TEE']);

        $paris = Inventory::factory()->create(['is_default' => true]);
        $lyon = Inventory::factory()->create(['is_default' => false]);

        $size = Attribute::factory()->create(['name' => 'Size', 'type' => FieldType::Checkbox, 'is_enabled' => true]);
        $small = AttributeValue::factory()->create(['attribute_id' => $size->id, 'value' => 'S']);
        $medium = AttributeValue::factory()->create(['attribute_id' => $size->id, 'value' => 'M']);
        $large = AttributeValue::factory()->create(['attribute_id' => $size->id, 'value' => 'L']);
        $material = Attribute::factory()->create(['name' => 'Material', 'type' => FieldType::Text, 'is_enabled' => true]);

        Livewire::test(ChooseProductAttributes::class, ['product' => $this->product])
            ->set('data', [
                'attributes' => [$size->id, $material->id],
                'values' => [
                    $size->id => [$small->id, $medium->id],
                    'custom_value' => [$material->id => 'Cotton'],
                ],
            ])
            ->call('store')
            ->assertHasNoErrors();

        $generator = Livewire::test(GenerateVariants::class, ['product' => $this->product]);

        expect(collect($generator->get('variants'))->map(fn (array $row): array => [$row['name'], $row['values']])->all())
            ->toBe([['S', [$small->id]], ['M', [$medium->id]]]);

        $generator->set('variants.0.price', '15.99')->set('variants.0.stock', 5)->call('generate');

        $tShirtS = $this->product->variants()->where('name', 'S')->firstOrFail();
        $tShirtM = $this->product->variants()->where('name', 'M')->firstOrFail();

        Livewire::test(VariantStock::class, ['variant' => $tShirtS])
            ->callAction('stock', ['inventory' => $lyon->id, 'quantity' => 4]);

        Livewire::test(VariantStock::class, ['variant' => $tShirtM])
            ->callAction('stock', ['inventory' => $lyon->id, 'quantity' => -2]);

        $tShirtM->prices()->create(['currency_id' => Currency::query()->where('code', 'EUR')->value('id'), 'amount' => 1500]);

        Livewire::test(ChooseProductAttributes::class, ['product' => $this->product])
            ->set('data', ['attributes' => [$size->id], 'values' => [$size->id => [$large->id]]])
            ->call('store');

        $generator = Livewire::test(GenerateVariants::class, ['product' => $this->product]);

        expect(collect($generator->get('variants'))->pluck('variant_id', 'name')->all())
            ->toBe(['S' => $tShirtS->id, 'M' => $tShirtM->id, 'L' => null])
            ->and(mb_substr_count($generator->html(), e(__('shopper::pages/products.variants.stock_managed_per_location'))))->toBe(2)
            ->and(mb_substr_count($generator->html(), 'fi-input-wrp fi-disabled'))->toBe(2)
            ->and(preg_match_all('/wire:model="variants\.\d+\.stock"\s+disabled="disabled"/', $generator->html()))->toBe(2)
            ->and($generator->html())->toContain('aria-label="'.e(__('shopper::forms.actions.delete_label', ['label' => 'L'])).'"')
            ->and(preg_match_all('/aria-describedby="variant-\w+-stock-hint"/', $generator->html()))->toBe(2)
            ->and(preg_match_all('/id="variant-\w+-stock-hint"/', $generator->html()))->toBe(2)
            ->and(mb_substr_count($generator->html(), ':aria-expanded="expanded"'))->toBe(3)
            ->and($tShirtS->prices()->pluck('amount')->all())->toBe([1599])
            ->and($generator->get('variants.0.price'))->toBe(15.99)
            ->and($generator->get('variants.1.price'))->toBe(0)
            ->and($generator->get('variants.1.stock'))->toBe(-2);

        $generator->set('variants.0.sku', 'TEE-MEDIUM')
            ->set('variants.1.sku', 'TEE-MEDIUM')
            ->call('generate')
            ->assertHasErrors(['variants.0.sku' => 'distinct', 'variants.1.sku' => 'distinct']);

        expect($this->product->variants()->count())->toBe(2);

        $generator->set('variants.0.sku', '')
            ->set('variants.1.name', 'Medium')
            ->set('variants.2.sku', '')
            ->set('variants.2.stock', 7)
            ->call('generate')
            ->assertHasNoErrors();

        $tShirtL = $this->product->variants()->where('name', 'L')->firstOrFail();

        expect($tShirtS->refresh()->sku)->toBeNull()
            ->and($tShirtL->sku)->toBeNull()
            ->and($tShirtS->stockInventory($paris->id))->toBe(5)
            ->and($tShirtS->stockInventory($lyon->id))->toBe(4)
            ->and($tShirtS->inventoryHistories()->count())->toBe(2)
            ->and($tShirtM->refresh()->only('name', 'sku'))->toBe(['name' => 'Medium', 'sku' => 'TEE-MEDIUM'])
            ->and($tShirtM->prices()->pluck('amount')->all())->toBe([1500])
            ->and($tShirtM->stockInventory($lyon->id))->toBe(-2)
            ->and($tShirtL->stockInventory($paris->id))->toBe(7)
            ->and($tShirtL->values()->get()->modelKeys())->toBe([$large->id])
            ->and(collect(Livewire::test(GenerateVariants::class, ['product' => $this->product])->get('variants'))->pluck('name')->all())
            ->toBe(['S', 'Medium', 'L']);

        Livewire::withoutLazyLoading()->test(Attributes::class, ['product' => $this->product])
            ->mountAction(TestAction::make('delete')->table(
                AttributeProduct::query()->where('attribute_value_id', $large->id)->firstOrFail()
            ))
            ->assertMountedActionModalSee(trans_choice('shopper::pages/products.attributes.variants_keep_value', 1, ['count' => 1, 'value' => 'L']));

        Livewire::withoutLazyLoading()->test(Attributes::class, ['product' => $this->product])
            ->mountAction(TestAction::make('delete')->table(
                AttributeProduct::query()->where('attribute_id', $material->id)->firstOrFail()
            ))
            ->assertMountedActionModalSee(__('filament-actions::modal.confirmation'))
            ->callMountedAction();

        expect($tShirtS->values()->get()->modelKeys())->toBe([$small->id])
            ->and($tShirtM->values()->get()->modelKeys())->toBe([$medium->id])
            ->and($tShirtL->values()->get()->modelKeys())->toBe([$large->id])
            ->and($this->product->options()->pluck('name')->unique()->values()->all())->toBe(['Size']);
    });

    it('matches each variant to its own option values when an option is added then a value removed', function (): void {
        $this->user->givePermissionTo('products.edit', 'attributes.delete');

        $size = Attribute::factory()->create(['name' => 'Size', 'type' => FieldType::Checkbox, 'is_enabled' => true]);
        $small = AttributeValue::factory()->create(['attribute_id' => $size->id, 'value' => 'S']);
        $medium = AttributeValue::factory()->create(['attribute_id' => $size->id, 'value' => 'M']);
        $color = Attribute::factory()->create(['name' => 'Color', 'type' => FieldType::Checkbox, 'is_enabled' => true]);
        $red = AttributeValue::factory()->create(['attribute_id' => $color->id, 'value' => 'Red']);
        $blue = AttributeValue::factory()->create(['attribute_id' => $color->id, 'value' => 'Blue']);

        Livewire::test(ChooseProductAttributes::class, ['product' => $this->product])
            ->set('data', ['attributes' => [$size->id], 'values' => [$size->id => [$small->id, $medium->id]]])
            ->call('store')
            ->assertHasNoErrors();

        Livewire::test(GenerateVariants::class, ['product' => $this->product])
            ->set('variants.0.sku', 'TEE-S')
            ->set('variants.1.sku', 'TEE-M')
            ->call('generate');

        $tShirtS = $this->product->variants()->where('sku', 'TEE-S')->firstOrFail();
        $tShirtM = $this->product->variants()->where('sku', 'TEE-M')->firstOrFail();

        Livewire::test(ChooseProductAttributes::class, ['product' => $this->product])
            ->set('data', ['attributes' => [$color->id], 'values' => [$color->id => [$red->id, $blue->id]]])
            ->call('store')
            ->assertHasNoErrors();

        $generator = Livewire::test(GenerateVariants::class, ['product' => $this->product->fresh()]);

        expect(collect($generator->get('variants'))->map(fn (array $row): array => [$row['name'], $row['variant_id']])->all())
            ->toBe([['S / Red', $tShirtS->id], ['S / Blue', null], ['M / Red', $tShirtM->id], ['M / Blue', null]]);

        $generator->set('variants.0.sku', 'TEE-M')
            ->set('variants.2.sku', 'TEE-S')
            ->call('generate')
            ->assertHasNoErrors();

        expect($tShirtS->refresh()->sku)->toBe('TEE-M')
            ->and($tShirtM->refresh()->sku)->toBe('TEE-S');

        $smallBlue = $this->product->variants()->where('name', 'S / Blue')->firstOrFail();
        $mediumBlue = $this->product->variants()->where('name', 'M / Blue')->firstOrFail();

        Livewire::withoutLazyLoading()->test(Attributes::class, ['product' => $this->product])
            ->callAction(TestAction::make('delete')->table(
                AttributeProduct::query()->where('attribute_value_id', $red->id)->firstOrFail()
            ));

        expect(collect(Livewire::test(GenerateVariants::class, ['product' => $this->product->fresh()])->get('variants'))
            ->map(fn (array $row): array => [$row['name'], $row['variant_id']])->all())
            ->toBe([['S / Blue', $smallBlue->id], ['M / Blue', $mediumBlue->id]]);
    });

    it('creates a new variant when the only value of an option is swapped for another', function (): void {
        $this->user->givePermissionTo('products.edit', 'attributes.delete', 'products.variants.delete');

        $size = Attribute::factory()->create(['name' => 'Size', 'type' => FieldType::Checkbox, 'is_enabled' => true]);
        $large = AttributeValue::factory()->create(['attribute_id' => $size->id, 'value' => 'L']);
        $extraLarge = AttributeValue::factory()->create(['attribute_id' => $size->id, 'value' => 'XL']);

        Livewire::test(ChooseProductAttributes::class, ['product' => $this->product])
            ->set('data', ['attributes' => [$size->id], 'values' => [$size->id => [$large->id]]])
            ->call('store')
            ->assertHasNoErrors();

        Livewire::test(GenerateVariants::class, ['product' => $this->product])
            ->set('variants.0.sku', 'TEE-L')
            ->set('variants.0.stock', 12)
            ->call('generate')
            ->assertHasNoErrors();

        $tShirtL = $this->product->variants()->where('sku', 'TEE-L')->firstOrFail();

        Livewire::withoutLazyLoading()->test(Attributes::class, ['product' => $this->product])
            ->callAction(TestAction::make('delete')->table(
                AttributeProduct::query()->where('attribute_value_id', $large->id)->firstOrFail()
            ));

        Livewire::test(ChooseProductAttributes::class, ['product' => $this->product->fresh()])
            ->set('data', ['attributes' => [$size->id], 'values' => [$size->id => [$extraLarge->id]]])
            ->call('store')
            ->assertHasNoErrors();

        $generator = Livewire::test(GenerateVariants::class, ['product' => $this->product->fresh()]);

        expect(collect($generator->get('variants'))->map(fn (array $row): array => [$row['name'], $row['variant_id'], $row['stock']])->all())
            ->toBe([['XL', null, 0]]);

        $generator->call('generate')->assertHasNoErrors();

        expect($this->product->variants()->pluck('name')->all())->toBe(['XL'])
            ->and(ProductVariant::query()->find($tShirtL->id))->toBeNull();
    });

    it('does not hand a variant stripped of its values by an earlier removal to another value', function (): void {
        $this->user->givePermissionTo('products.edit');

        $size = Attribute::factory()->create(['name' => 'Size', 'type' => FieldType::Checkbox, 'is_enabled' => true]);
        $small = AttributeValue::factory()->create(['attribute_id' => $size->id, 'value' => 'S']);
        $extraLarge = AttributeValue::factory()->create(['attribute_id' => $size->id, 'value' => 'XL']);

        Livewire::test(ChooseProductAttributes::class, ['product' => $this->product])
            ->set('data', ['attributes' => [$size->id], 'values' => [$size->id => [$small->id, $extraLarge->id]]])
            ->call('store')
            ->assertHasNoErrors();

        $tShirtS = ProductVariant::factory()->create(['product_id' => $this->product->id, 'name' => 'S']);
        $tShirtS->values()->attach($small->id);
        ProductVariant::factory()->create(['product_id' => $this->product->id, 'name' => 'L']);

        expect(collect(Livewire::test(GenerateVariants::class, ['product' => $this->product->fresh()])->get('variants'))
            ->map(fn (array $row): array => [$row['name'], $row['variant_id']])->all())
            ->toBe([['S', $tShirtS->id], ['XL', null]]);
    });

    it('does not guess which of several variants without values takes a value none of them is named after', function (): void {
        $this->user->givePermissionTo('products.edit');

        $tShirtS = ProductVariant::factory()->create(['product_id' => $this->product->id, 'name' => 'S']);
        $tShirtM = ProductVariant::factory()->create(['product_id' => $this->product->id, 'name' => 'M']);
        ProductVariant::factory()->create(['product_id' => $this->product->id, 'name' => 'L']);

        $size = Attribute::factory()->create(['name' => 'Size', 'type' => FieldType::Checkbox, 'is_enabled' => true]);
        $values = collect(['S', 'M', 'XL'])->map(fn (string $value): int => AttributeValue::factory()->create(['attribute_id' => $size->id, 'value' => $value])->id);

        Livewire::test(ChooseProductAttributes::class, ['product' => $this->product])
            ->set('data', ['attributes' => [$size->id], 'values' => [$size->id => $values->all()]])
            ->call('store')
            ->assertHasNoErrors();

        expect(collect(Livewire::test(GenerateVariants::class, ['product' => $this->product->fresh()])->get('variants'))
            ->map(fn (array $row): array => [$row['name'], $row['variant_id']])->all())
            ->toBe([['S', $tShirtS->id], ['M', $tShirtM->id], ['XL', null]]);
    });

    it('gives each variant that lost its option values back to the permutation of its name', function (): void {
        $this->user->givePermissionTo('products.edit');

        $color = Attribute::factory()->create(['name' => 'Color', 'type' => FieldType::Checkbox, 'is_enabled' => true]);
        $red = AttributeValue::factory()->create(['attribute_id' => $color->id, 'value' => 'Red']);
        $blue = AttributeValue::factory()->create(['attribute_id' => $color->id, 'value' => 'Blue']);

        Livewire::test(ChooseProductAttributes::class, ['product' => $this->product])
            ->set('data', ['attributes' => [$color->id], 'values' => [$color->id => [$red->id, $blue->id]]])
            ->call('store')
            ->assertHasNoErrors();

        $blueTee = ProductVariant::factory()->create(['product_id' => $this->product->id, 'name' => 'Blue', 'sku' => 'TEE-BLUE']);
        $redTee = ProductVariant::factory()->create(['product_id' => $this->product->id, 'name' => 'Red', 'sku' => 'TEE-RED']);

        expect(collect(Livewire::test(GenerateVariants::class, ['product' => $this->product->fresh()])->get('variants'))
            ->map(fn (array $row): array => [$row['name'], $row['variant_id'], $row['sku']])->all())
            ->toBe([['Red', $redTee->id, 'TEE-RED'], ['Blue', $blueTee->id, 'TEE-BLUE']]);
    });
})->group('livewire', 'slideovers', 'products');
