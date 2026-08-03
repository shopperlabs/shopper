<?php

declare(strict_types=1);

use Shopper\Core\Enum\FieldType;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeValue;
use Shopper\Core\Models\Brand;
use Shopper\Core\Models\Category;
use Shopper\Core\Models\Collection;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductTag;

uses(Tests\Api\TestCase::class);

it('shows a category by slug and hides disabled ones', function (): void {
    $enabled = Category::factory()->create(['name' => 'CatEnabled', 'slug' => 'cat-enabled', 'is_enabled' => true]);
    $disabled = Category::factory()->create(['name' => 'CatDisabled', 'slug' => 'cat-disabled', 'is_enabled' => false]);

    $this->getJson('/store/categories/'.$enabled->slug)
        ->assertOk()
        ->assertJsonPath('data.type', 'categories')
        ->assertJsonPath('data.id', $enabled->public_id)
        ->assertJsonPath('data.attributes.name', 'CatEnabled');

    $ids = collect($this->getJson('/store/categories?filter[name]=Cat')->assertOk()->json('data'))->pluck('id');
    expect($ids)->toContain($enabled->public_id)->and($ids)->not->toContain($disabled->public_id);

    $this->getJson('/store/categories/'.$disabled->slug)->assertNotFound();
});

it('lists only published collections and shows one by slug', function (): void {
    $published = Collection::factory()->create(['name' => 'ColPublished', 'slug' => 'col-published', 'published_at' => now()]);
    $draft = Collection::factory()->create(['name' => 'ColDraft', 'slug' => 'col-draft', 'published_at' => now()->addYear()]);

    $ids = collect($this->getJson('/store/collections?filter[name]=Col')->assertOk()->json('data'))->pluck('id');
    expect($ids)->toContain($published->public_id)->and($ids)->not->toContain($draft->public_id);

    $this->getJson('/store/collections/'.$published->slug)
        ->assertOk()
        ->assertJsonPath('data.type', 'collections')
        ->assertJsonPath('data.id', $published->public_id);
});

it('shows a brand and includes its products on demand', function (): void {
    $brand = Brand::factory()->create(['name' => 'BrandOne', 'slug' => 'brand-one', 'is_enabled' => true]);
    $product = Product::factory()->publish()->create(['brand_id' => $brand->id]);

    $this->getJson('/store/brands/'.$brand->slug.'?include=products')
        ->assertOk()
        ->assertJsonPath('data.type', 'brands')
        ->assertJsonPath('data.id', $brand->public_id)
        ->assertJsonPath('included.0.type', 'products')
        ->assertJsonPath('included.0.id', $product->public_id);
});

it('hides disabled brands from the listing', function (): void {
    $enabled = Brand::factory()->create(['name' => 'BrandVisible', 'is_enabled' => true]);
    $disabled = Brand::factory()->create(['name' => 'BrandHidden', 'is_enabled' => false]);

    $ids = collect($this->getJson('/store/brands?filter[name]=Brand')->assertOk()->json('data'))->pluck('id');
    expect($ids)->toContain($enabled->public_id)->and($ids)->not->toContain($disabled->public_id);
});

it('lists tags and exposes them on a product through the include', function (): void {
    $tag = ProductTag::factory()->create(['name' => 'TagSummer']);
    $other = ProductTag::factory()->create(['name' => 'TagWinter']);
    $product = Product::factory()->publish()->create();
    $product->tags()->attach($tag->id);

    $ids = collect($this->getJson('/store/tags?filter[name]=Tag')->assertOk()->json('data'))->pluck('id');
    expect($ids)->toContain($tag->public_id)->and($ids)->toContain($other->public_id);

    $this->getJson('/store/products/'.$product->slug.'?include=tags')
        ->assertOk()
        ->assertJsonPath('included.0.type', 'tags')
        ->assertJsonPath('included.0.id', $tag->public_id)
        ->assertJsonPath('included.0.attributes.name', 'TagSummer');
});

it('shows only public products through the tag products include', function (): void {
    $tag = ProductTag::factory()->create(['name' => 'TagVisible']);
    $published = Product::factory()->publish()->create();
    $draft = Product::factory()->create(['published_at' => now()->addYear()]);
    $tag->products()->attach([$published->id, $draft->id]);

    $ids = collect($this->getJson('/store/tags?filter[name]=TagVisible&include=products')->assertOk()->json('included') ?? [])->pluck('id');
    expect($ids)->toContain($published->public_id)->and($ids)->not->toContain($draft->public_id);
});

it('lists only filterable facet attributes with their embedded values', function (): void {
    $attribute = Attribute::factory()->create([
        'name' => 'AttrColor',
        'type' => FieldType::ColorPicker,
        'is_enabled' => true,
        'is_filterable' => true,
    ]);
    AttributeValue::factory()->create(['attribute_id' => $attribute->id, 'value' => 'Red', 'key' => 'attr-red']);

    Attribute::factory()->create([
        'name' => 'AttrDisabled',
        'type' => FieldType::Select,
        'is_enabled' => false,
        'is_filterable' => true,
    ]);
    Attribute::factory()->create([
        'name' => 'AttrInternal',
        'type' => FieldType::Select,
        'is_enabled' => true,
        'is_filterable' => false,
    ]);
    Attribute::factory()->create([
        'name' => 'AttrText',
        'type' => FieldType::Text,
        'is_enabled' => true,
        'is_filterable' => true,
    ]);

    $response = $this->getJson('/store/attributes?filter[name]=Attr')->assertOk();

    $names = collect($response->json('data'))->pluck('attributes.name');
    expect($names)->toContain('AttrColor')
        ->and($names)->not->toContain('AttrDisabled')
        ->and($names)->not->toContain('AttrInternal')
        ->and($names)->not->toContain('AttrText');

    $row = collect($response->json('data'))->firstWhere('id', $attribute->public_id);
    $value = collect($row['attributes']['values'])->firstWhere('key', 'attr-red');
    expect($value)->not->toBeNull()
        ->and($value['value'])->toBe('Red')
        ->and($row['attributes'])->not->toHaveKeys(['is_enabled', 'is_filterable', 'is_searchable']);
});
