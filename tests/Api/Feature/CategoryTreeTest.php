<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Tests\Core\Stubs\Category;

uses(Tests\Api\TestCase::class);

function category(string $name, ?Category $parent = null): Category
{
    return Category::factory()->create([
        'name' => $name,
        'slug' => $name,
        'is_enabled' => true,
        'parent_id' => $parent?->id,
    ]);
}

it('links a child to its parent through the public identifier', function (): void {
    $parent = category('Furniture');
    $child = category('Sofas', $parent);

    $this->getJson('/store/categories/'.$child->slug)
        ->assertOk()
        ->assertJsonPath('data.id', $child->public_id)
        ->assertJsonPath('data.attributes.parent_id', $parent->public_id);

    $this->getJson('/store/categories/'.$parent->slug)
        ->assertOk()
        ->assertJsonPath('data.attributes.parent_id', null);
});

it('builds a tree from a listing without one query per row', function (): void {
    $parent = category('Furniture');

    foreach (['Sofas', 'Tables', 'Chairs', 'Lamps'] as $name) {
        category($name, $parent);
    }

    DB::enableQueryLog();

    $data = collect($this->getJson('/store/categories')->assertOk()->json('data'));

    $queries = count(DB::getQueryLog());

    $byId = $data->keyBy('id');
    $children = $data->filter(fn (array $row): bool => $row['attributes']['parent_id'] !== null);

    expect($children)->toHaveCount(4)
        ->and($children->every(fn (array $row): bool => $byId->has($row['attributes']['parent_id'])))->toBeTrue()
        ->and($queries)->toBeLessThan(6);
});

it('resolves the parent identifier through the children include too', function (): void {
    $parent = category('Furniture');
    $child = category('Sofas', $parent);

    $included = collect(
        $this->getJson('/store/categories/'.$parent->slug.'?include=children')->assertOk()->json('included')
    )->firstWhere('id', $child->public_id);

    expect($included['attributes']['parent_id'])->toBe($parent->public_id);
});

it('returns the nested public tree in one call', function (): void {
    $root = category('TreeRoot');
    $child = category('TreeChild', $root);
    $grandchild = category('TreeGrand', $child);

    $disabledRoot = Category::factory()->create(['name' => 'TreeHidden', 'is_enabled' => false]);
    Category::factory()->create(['name' => 'TreeOrphan', 'is_enabled' => true, 'parent_id' => $disabledRoot->id]);

    $data = collect($this->getJson('/store/categories/tree')->assertOk()->json('data'));

    $names = $data->pluck('name');
    expect($names)->toContain('TreeRoot')
        ->and($names)->not->toContain('TreeHidden')
        ->and($names)->not->toContain('TreeOrphan');

    $rootNode = $data->firstWhere('name', 'TreeRoot');
    expect($rootNode['id'])->toBe($root->public_id)
        ->and($rootNode['slug'])->toBe($root->fresh()->slug)
        ->and($rootNode['children'][0]['id'])->toBe($child->public_id)
        ->and($rootNode['children'][0]['children'][0]['id'])->toBe($grandchild->public_id)
        ->and($rootNode['children'][0]['children'][0]['children'])->toBe([]);
});

it('orders the tree nodes by position on every level', function (): void {
    $root = category('PosRoot');
    $second = category('PosSecond', $root);
    $first = category('PosFirst', $root);
    $second->update(['position' => 2]);
    $first->update(['position' => 1]);

    $rootNode = collect($this->getJson('/store/categories/tree')->assertOk()->json('data'))
        ->firstWhere('name', 'PosRoot');

    expect(collect($rootNode['children'])->pluck('name')->all())->toBe(['PosFirst', 'PosSecond']);
});
