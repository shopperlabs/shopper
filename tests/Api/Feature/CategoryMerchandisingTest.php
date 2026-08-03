<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Models\Channel;
use Shopper\Core\Models\Product;
use Tests\Core\Stubs\Category;

uses(Tests\Api\TestCase::class);

function node(string $name, ?Category $parent = null): Category
{
    return Category::factory()->create([
        'name' => $name,
        'slug' => $name,
        'is_enabled' => true,
        'parent_id' => $parent?->id,
    ]);
}

it('exposes the depth of a category', function (): void {
    $furniture = node('Furniture');
    $sofas = node('Sofas', $furniture);
    $corner = node('Corner', $sofas);

    $this->getJson('/store/categories/'.$furniture->slug)
        ->assertOk()
        ->assertJsonPath('data.attributes.depth', 0);

    $this->getJson('/store/categories/'.$corner->slug)
        ->assertOk()
        ->assertJsonPath('data.attributes.depth', 2);
});

it('computes the depth beyond three levels', function (): void {
    $level = node('LevelA');

    foreach (['LevelB', 'LevelC', 'LevelD', 'LevelE'] as $name) {
        $level = node($name, $level);
    }

    $this->getJson('/store/categories/'.$level->slug)
        ->assertOk()
        ->assertJsonPath('data.attributes.depth', 4);
});

it('carries the depth on every row of the listing without a query per row', function (): void {
    $furniture = node('Furniture');

    foreach (['Sofas', 'Tables', 'Chairs'] as $name) {
        node($name, $furniture);
    }

    DB::enableQueryLog();

    $data = collect($this->getJson('/store/categories')->assertOk()->json('data'));

    $queries = count(DB::getQueryLog());

    expect($data)->toHaveCount(4)
        ->and($data->every(fn (array $row): bool => $row['attributes']['depth'] !== null))->toBeTrue()
        ->and($queries)->toBeLessThan(6);
});

it('includes the ancestors root first for the breadcrumb', function (): void {
    $furniture = node('Furniture');
    $sofas = node('Sofas', $furniture);
    $corner = node('Corner', $sofas);
    node('Garden');

    $response = $this->getJson('/store/categories/'.$corner->slug.'?include=ancestors')->assertOk();

    $ids = collect($response->json('data.relationships.ancestors.data'))->pluck('id');

    $included = collect($response->json('included'))->firstWhere('id', $furniture->public_id);

    expect($ids->all())->toBe([$furniture->public_id, $sofas->public_id])
        ->and($included['attributes']['depth'])->toBeNull();
});

it('prunes a category under a disabled ancestor from every response', function (): void {
    $furniture = node('Furniture');
    $sofas = node('Sofas', $furniture);
    $corner = node('Corner', $sofas);

    $sofas->update(['is_enabled' => false]);

    $this->getJson('/store/categories/'.$corner->slug)->assertNotFound();

    $ids = collect($this->getJson('/store/categories')->assertOk()->json('data'))->pluck('id');

    expect($ids)->toContain($furniture->public_id)
        ->and($ids)->not->toContain($sofas->public_id)
        ->and($ids)->not->toContain($corner->public_id);
});

it('cursor-paginates the listing without gaps or duplicates', function (): void {
    $furniture = node('Furniture');

    foreach (['Sofas', 'Tables', 'Chairs', 'Lamps', 'Rugs'] as $name) {
        node($name, $furniture);
    }

    $ids = [];
    $url = '/store/categories?page[size]=2&page[cursor]=';

    do {
        $response = $this->getJson($url)->assertOk();

        foreach ($response->json('data') as $row) {
            $ids[] = $row['id'];
        }

        $url = $response->json('links.next');
    } while ($url !== null);

    expect(collect($ids)->unique())->toHaveCount(6);
});

it('combines a sort and a filter on the listing', function (): void {
    node('Alpha');
    node('Beta');
    node('Alphabet');

    $names = collect($this->getJson('/store/categories?sort=name&filter[name]=Alpha')->assertOk()->json('data'))
        ->pluck('attributes.name');

    expect($names->all())->toBe(['Alpha', 'Alphabet']);
});

it('counts the published products of the whole subtree once each', function (): void {
    $furniture = node('Furniture');
    $sofas = node('Sofas', $furniture);
    $garden = node('Garden');

    Product::factory()->create()->categories()->attach([$furniture->id, $sofas->id]);
    Product::factory()->create()->categories()->attach($sofas->id);
    Product::factory()->unpublished()->create()->categories()->attach($sofas->id);
    Product::factory()->create()->categories()->attach($garden->id);

    $this->getJson('/store/categories/'.$furniture->slug.'?include=products_count')
        ->assertOk()
        ->assertJsonPath('data.attributes.products_count', 2);
});

it('excludes disabled branches from the subtree products count', function (): void {
    $furniture = node('Furniture');
    $hidden = node('Hidden', $furniture);

    Product::factory()->create()->categories()->attach($furniture->id);
    Product::factory()->create()->categories()->attach($hidden->id);

    $hidden->update(['is_enabled' => false]);

    $this->getJson('/store/categories/'.$furniture->slug.'?include=products_count')
        ->assertOk()
        ->assertJsonPath('data.attributes.products_count', 1);
});

it('scopes the subtree products count to the resolved channel', function (): void {
    $channel = Channel::factory()->create(['slug' => 'webstore', 'is_enabled' => true, 'is_default' => false]);
    $furniture = node('Furniture');

    $onChannel = Product::factory()->create();
    $onChannel->categories()->attach($furniture->id);
    $onChannel->channels()->attach($channel->id);

    Product::factory()->create()->categories()->attach($furniture->id);

    $this->getJson('/store/categories/'.$furniture->slug.'?include=products_count')
        ->assertOk()
        ->assertJsonPath('data.attributes.products_count', 2);

    $this->getJson('/store/categories/'.$furniture->slug.'?include=products_count', ['X-Shopper-Channel' => 'webstore'])
        ->assertOk()
        ->assertJsonPath('data.attributes.products_count', 1);
});

it('carries the subtree products count on the listing only when requested', function (): void {
    $furniture = node('Furniture');
    $sofas = node('Sofas', $furniture);

    Product::factory()->create()->categories()->attach($sofas->id);

    $rows = collect($this->getJson('/store/categories?include=products_count')->assertOk()->json('data'))
        ->keyBy('attributes.slug');

    expect($rows[$furniture->slug]['attributes']['products_count'])->toBe(1)
        ->and($rows[$sofas->slug]['attributes']['products_count'])->toBe(1);

    $bare = collect($this->getJson('/store/categories')->assertOk()->json('data'))->first();

    expect($bare['attributes'])->not->toHaveKey('products_count');
});

it('counts the subtrees of a page without a recursive query per row', function (): void {
    $furniture = node('Furniture');

    foreach (['Sofas', 'Tables', 'Chairs', 'Lamps'] as $name) {
        Product::factory()->create()->categories()->attach(node($name, $furniture)->id);
    }

    $this->getJson('/store/categories?include=products_count')->assertOk();

    DB::enableQueryLog();

    $this->getJson('/store/categories?include=products_count')->assertOk();

    $queries = collect(DB::getQueryLog())->pluck('query');

    expect($queries->filter(fn (string $sql): bool => str_contains($sql, 'recursive')))->toBeEmpty()
        ->and($queries)->toHaveCount(5);
});

it('drops a category from the listing as soon as the merchant disables it', function (): void {
    $furniture = node('Furniture');
    $sofas = node('Sofas', $furniture);

    $before = collect($this->getJson('/store/categories')->assertOk()->json('data'))->pluck('id');

    $sofas->update(['is_enabled' => false]);

    $after = collect($this->getJson('/store/categories')->assertOk()->json('data'))->pluck('id');

    expect($before)->toContain($sofas->public_id)
        ->and($after)->not->toContain($sofas->public_id);
});

it('makes a category visible again once re-enabled', function (): void {
    $furniture = node('Furniture');
    $sofas = node('Sofas', $furniture);

    $sofas->update(['is_enabled' => false]);
    $this->getJson('/store/categories/'.$sofas->slug)->assertNotFound();

    $sofas->update(['is_enabled' => true]);
    $this->getJson('/store/categories/'.$sofas->slug)->assertOk();
});

it('invalidates the cached tree on the database cache store', function (): void {
    if (! Schema::hasTable('cache')) {
        Schema::create('cache', function (Blueprint $table): void {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });
    }

    config(['cache.default' => 'database']);

    $furniture = node('Furniture');
    $sofas = node('Sofas', $furniture);

    $this->getJson('/store/categories')->assertOk()->assertJsonCount(2, 'data');

    $sofas->update(['is_enabled' => false]);

    $ids = collect($this->getJson('/store/categories')->assertOk()->json('data'))->pluck('id');

    expect($ids->all())->toBe([$furniture->public_id]);
});

it('prunes the same branches with the tree cache turned off', function (): void {
    config(['shopper.core.cache.category_tree' => null]);

    $furniture = node('Furniture');
    $hidden = node('Hidden', $furniture);
    node('Deep', $hidden);

    $hidden->update(['is_enabled' => false]);

    $ids = collect($this->getJson('/store/categories')->assertOk()->json('data'))->pluck('id');

    expect($ids->all())->toBe([$furniture->public_id]);
});

it('keeps the subtree count equal to what the tree filter lists', function (): void {
    $furniture = node('Furniture');
    $sofas = node('Sofas', $furniture);

    Product::factory()->create()->categories()->attach([$furniture->id, $sofas->id]);
    Product::factory()->create()->categories()->attach($sofas->id);
    Product::factory()->unpublished()->create()->categories()->attach($sofas->id);

    $count = $this->getJson('/store/categories/'.$furniture->slug.'?include=products_count')
        ->assertOk()
        ->json('data.attributes.products_count');

    $listed = $this->getJson('/store/products?filter[category_tree]='.$furniture->slug.'&page[size]=100')
        ->assertOk()
        ->json('data');

    expect($count)->toBe(count($listed));
});
