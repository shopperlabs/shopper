<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Contracts\Category as CategoryContract;
use Shopper\Livewire\SlideOvers\CategoryForm;
use Tests\Core\Stubs\Category;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('add_categories', 'edit_categories');
    $this->actingAs($this->user);
});

describe(CategoryForm::class, function (): void {
    it('blocks opening the form for users without category permissions', function (): void {
        $this->actingAs(User::factory()->create());

        Livewire::test(CategoryForm::class)
            ->assertForbidden();
    });

    it('can validate required fields on add category form', function (): void {
        Livewire::test(CategoryForm::class)
            ->fillForm()
            ->call('save')
            ->assertHasFormErrors(['name' => 'required']);
    });

    it('can create a category', function (): void {
        Livewire::test(CategoryForm::class)
            ->fillForm([
                'name' => 'My new Category',
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirectToRoute('shopper.categories.index');

        expect(resolve(CategoryContract::class)::query()->count())->toBe(1);
    });

    it('will generate a slug when category slug already exists', function (): void {
        Category::factory()->create(['name' => 'Old category', 'slug' => 'my-first-category']);

        Livewire::test(CategoryForm::class)
            ->fillForm([
                'name' => 'My first category',
                'slug' => 'my-first-category',
            ])
            ->call('save')
            ->assertRedirectToRoute('shopper.categories.index');

        expect(resolve(CategoryContract::class)::query()->count())
            ->toBe(2)
            ->and(resolve(CategoryContract::class)::query()->latest()->first()?->slug)
            ->toBe('my-first-category-1');
    });

    it('generates the slug from the name while typing on create', function (): void {
        $component = Livewire::test(CategoryForm::class)
            ->set('data.name', 'Summer Shoes');

        expect($component->get('data.slug'))->toBe('summer-shoes');
    });

    it('keeps the slug when renaming an existing category', function (): void {
        $category = Category::factory()->create(['name' => 'Shoes', 'slug' => 'shoes']);

        $component = Livewire::test(CategoryForm::class, ['category' => $category])
            ->set('data.name', 'Footwear');

        expect($component->get('data.slug'))->toBe('shoes');
    });

    it('can create category with parent', function (): void {
        $parent = Category::factory()->create(['name' => 'Parent', 'is_enabled' => true]);

        Livewire::test(CategoryForm::class)
            ->fillForm([
                'name' => 'My new Category',
                'parent_id' => $parent->id,
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirectToRoute('shopper.categories.index');

        expect(resolve(CategoryContract::class)::query()->count())->toBe(2);
    });

    it('has parent_id field null when parent category is deleted', function (): void {
        $parent = Category::factory()->create(['name' => 'Parent']);
        $child = Category::factory()->create(['name' => 'Child', 'parent_id' => $parent->id]);

        expect($child->parent_id)->toBe($parent->id);

        $parent->delete();
        $child->refresh();

        expect($child->parent_id)->toBeNull();
    });
})->group('livewire', 'slideovers', 'categories');
