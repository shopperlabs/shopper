<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Category;
use Shopper\Core\Models\User;
use Shopper\Core\Repositories\CategoryRepository;
use Shopper\Livewire\SlideOvers\CategoryForm;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('add_categories', 'edit_categories');
    $this->actingAs($this->user);
});

describe(CategoryForm::class, function (): void {
    it('can validate required fields on add category form', function (): void {
        Livewire::test(CategoryForm::class)
            ->assertFormExists()
            ->fillForm()
            ->call('save')
            ->assertHasFormErrors(['name' => 'required']);
    });

    it('can create a category', function (): void {
        Livewire::test(CategoryForm::class)
            ->assertFormExists()
            ->fillForm([
                'name' => 'My new Category',
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirectToRoute('shopper.categories.index');

        expect((new CategoryRepository)->count())->toBe(1);
    });

    it('will generate a slug when brand slug already exists', function (): void {
        Category::factory()->create(['name' => 'Old category', 'slug' => 'my-first-category']);

        Livewire::test(CategoryForm::class)
            ->assertFormExists()
            ->fillForm([
                'name' => 'My first category',
            ])
            ->call('save')
            ->assertRedirectToRoute('shopper.categories.index');

        expect((new CategoryRepository)->count())
            ->toBe(2)
            ->and((new CategoryRepository)->getById(2)?->slug)
            ->toBe('my-first-category-1');
    });

    it('can create category with parent', function (): void {
        $parent = Category::factory()->create(['name' => 'Parent']);

        Livewire::test(CategoryForm::class)
            ->assertFormExists()
            ->fillForm([
                'name' => 'My new Category',
                'parent_id' => $parent->id,
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirectToRoute('shopper.categories.index');

        expect((new CategoryRepository)->count())->toBe(2);
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
