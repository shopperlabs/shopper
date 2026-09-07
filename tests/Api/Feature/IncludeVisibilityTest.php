<?php

declare(strict_types=1);

use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Zone;
use Symfony\Component\HttpFoundation\Response;
use Tests\Core\Stubs\Brand;
use Tests\Core\Stubs\Category;
use Tests\Core\Stubs\Collection;
use Tests\Core\Stubs\Product;

uses(Tests\Api\TestCase::class);

/**
 * @return array<int, string>
 */
function includedNames(object $test, string $path): array
{
    return collect($test->getJson($path)->assertOk()->json('included'))
        ->pluck('attributes.name')
        ->all();
}

it('never includes a disabled brand', function (): void {
    $hidden = Brand::factory()->create(['name' => 'Hidden', 'slug' => 'hidden', 'is_enabled' => false]);
    $product = Product::factory()->publish()->create(['name' => 'P', 'type' => ProductType::Standard, 'brand_id' => $hidden->id]);

    expect(includedNames($this, '/store/products/'.$product->slug.'?include=brand'))->toBe([]);
});

it('never includes a disabled category', function (): void {
    $visible = Category::factory()->create(['name' => 'Visible', 'slug' => 'visible', 'is_enabled' => true]);
    $hidden = Category::factory()->create(['name' => 'Hidden', 'slug' => 'hidden', 'is_enabled' => false]);

    $product = Product::factory()->publish()->create(['name' => 'P', 'type' => ProductType::Standard]);
    $product->categories()->attach([$visible->id, $hidden->id]);

    expect(includedNames($this, '/store/products/'.$product->slug.'?include=categories'))->toBe(['Visible']);
});

it('never includes a category hidden through a disabled ancestor', function (): void {
    $disabledRoot = Category::factory()->create(['name' => 'AncestorOff', 'slug' => 'ancestor-off', 'is_enabled' => false]);
    $orphan = Category::factory()->create(['name' => 'OrphanOn', 'slug' => 'orphan-on', 'is_enabled' => true, 'parent_id' => $disabledRoot->id]);
    $visible = Category::factory()->create(['name' => 'StillOn', 'slug' => 'still-on', 'is_enabled' => true]);

    $product = Product::factory()->publish()->create(['name' => 'P2', 'type' => ProductType::Standard]);
    $product->categories()->attach([$visible->id, $orphan->id]);

    expect(includedNames($this, '/store/products/'.$product->slug.'?include=categories'))->toBe(['StillOn']);
});

it('never includes a collection that is not published yet', function (): void {
    $live = Collection::factory()->create(['name' => 'Live', 'slug' => 'live', 'published_at' => now()->subDay()]);
    $embargoed = Collection::factory()->create(['name' => 'Embargoed', 'slug' => 'embargoed', 'published_at' => now()->addYear()]);

    $product = Product::factory()->publish()->create(['name' => 'P', 'type' => ProductType::Standard]);
    $product->collections()->attach([$live->id, $embargoed->id]);

    expect(includedNames($this, '/store/products/'.$product->slug.'?include=collections'))->toBe(['Live']);
});

it('never includes a disabled child or parent category', function (): void {
    $root = Category::factory()->create(['name' => 'Root', 'slug' => 'root', 'is_enabled' => true]);
    Category::factory()->create(['name' => 'ChildOn', 'slug' => 'child-on', 'is_enabled' => true, 'parent_id' => $root->id]);
    Category::factory()->create(['name' => 'ChildOff', 'slug' => 'child-off', 'is_enabled' => false, 'parent_id' => $root->id]);

    expect(includedNames($this, '/store/categories/'.$root->slug.'?include=children'))->toBe(['ChildOn']);

    $hiddenRoot = Category::factory()->create(['name' => 'HiddenRoot', 'slug' => 'hidden-root', 'is_enabled' => false]);
    $child = Category::factory()->create(['name' => 'Orphan', 'slug' => 'orphan', 'is_enabled' => true, 'parent_id' => $hiddenRoot->id]);

    $this->getJson('/store/categories/'.$child->slug.'?include=parent')->assertNotFound();
});

it('never includes a disabled zone', function (): void {
    $country = Country::query()->where('cca2', 'FR')->firstOrFail();

    $enabled = Zone::factory()->create(['name' => 'Europe', 'code' => 'eu', 'is_enabled' => true]);
    $disabled = Zone::factory()->create(['name' => 'Hidden', 'code' => 'hz', 'is_enabled' => false]);

    $country->zones()->attach([$enabled->id, $disabled->id]);

    expect(includedNames($this, '/store/countries/FR?include=zones'))->toBe(['Europe']);
});

it('includes a category shared by several products once', function (): void {
    $shared = Category::factory()->create(['name' => 'Shared', 'slug' => 'shared', 'is_enabled' => true]);

    foreach (['A', 'B', 'C'] as $name) {
        Product::factory()->publish()->create(['name' => $name, 'type' => ProductType::Standard])->categories()->attach($shared);
    }

    expect(includedNames($this, '/store/products?include=categories'))->toBe(['Shared']);
});

it('never includes a disabled child on the category listing', function (): void {
    $root = Category::factory()->create(['name' => 'Root', 'slug' => 'root', 'is_enabled' => true]);
    Category::factory()->create(['name' => 'ChildOn', 'slug' => 'child-on', 'is_enabled' => true, 'parent_id' => $root->id]);
    Category::factory()->create(['name' => 'ChildOff', 'slug' => 'child-off', 'is_enabled' => false, 'parent_id' => $root->id]);

    expect(includedNames($this, '/store/categories?include=children'))->toBe(['ChildOn']);
});

it('never includes a disabled category on the product listing', function (): void {
    $visible = Category::factory()->create(['name' => 'Visible', 'slug' => 'visible', 'is_enabled' => true]);
    $hidden = Category::factory()->create(['name' => 'Hidden', 'slug' => 'hidden', 'is_enabled' => false]);

    $product = Product::factory()->publish()->create(['name' => 'P', 'type' => ProductType::Standard]);
    $product->categories()->attach([$visible->id, $hidden->id]);

    expect(includedNames($this, '/store/products?include=categories'))->toBe(['Visible']);
});

it('includes only the requested level of the category tree', function (): void {
    $root = Category::factory()->create(['name' => 'Root', 'slug' => 'root', 'is_enabled' => true]);
    $mid = Category::factory()->create(['name' => 'Mid', 'slug' => 'mid', 'is_enabled' => true, 'parent_id' => $root->id]);
    $leaf = Category::factory()->create(['name' => 'Leaf', 'slug' => 'leaf', 'is_enabled' => true, 'parent_id' => $mid->id]);

    expect(includedNames($this, '/store/categories/'.$leaf->slug.'?include=parent'))->toBe(['Mid']);
});

it('rejects an include that is not on the allowlist of a detail endpoint', function (): void {
    $product = Product::factory()->publish()->create(['name' => 'P', 'type' => ProductType::Standard]);

    $this->getJson('/store/products/'.$product->slug.'?include=categories.parent')
        ->assertStatus(Response::HTTP_BAD_REQUEST);
});

it('respects sparse fieldsets on an included resource', function (): void {
    $category = Category::factory()->create(['name' => 'Sofas', 'slug' => 'sofas', 'is_enabled' => true]);
    $product = Product::factory()->publish()->create(['name' => 'P', 'type' => ProductType::Standard]);
    $product->categories()->attach($category);

    $included = collect($this->getJson('/store/products/'.$product->slug.'?include=categories&fields[categories]=name')->assertOk()->json('included'))->sole();

    expect($included['attributes'])->toBe(['name' => 'Sofas']);
});
