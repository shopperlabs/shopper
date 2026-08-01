<?php

declare(strict_types=1);

use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Zone;
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

    $this->getJson('/store/categories/'.$child->slug.'?include=parent')
        ->assertOk()
        ->assertJsonPath('included', null)
        ->assertJsonPath('data.attributes.parent_id', null);
});

it('never includes a disabled zone', function (): void {
    $country = Country::query()->where('cca2', 'FR')->firstOrFail();

    $enabled = Zone::factory()->create(['name' => 'Europe', 'code' => 'eu', 'is_enabled' => true]);
    $disabled = Zone::factory()->create(['name' => 'Hidden', 'code' => 'hz', 'is_enabled' => false]);

    $country->zones()->attach([$enabled->id, $disabled->id]);

    expect(includedNames($this, '/store/countries/FR?include=zones'))->toBe(['Europe']);
});
