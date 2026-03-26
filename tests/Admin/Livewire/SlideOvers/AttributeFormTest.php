<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\FieldType;
use Shopper\Core\Models\Attribute;
use Shopper\Livewire\SlideOvers\AttributeForm;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('add_attributes');
    $this->actingAs($this->user);
});

describe(AttributeForm::class, function (): void {
    it('can render attribute form component', function (): void {
        Livewire::test(AttributeForm::class)
            ->assertOk();
    });

    it('requires add_attributes or edit_attributes permission', function (): void {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(AttributeForm::class)
            ->assertForbidden();
    });

    it('can create new attribute', function (): void {
        Livewire::test(AttributeForm::class)
            ->assertFormExists()
            ->fillForm([
                'name' => 'Color',
                'slug' => 'color',
                'type' => FieldType::Select(),
                'is_enabled' => true,
            ])
            ->call('store')
            ->assertHasNoFormErrors();

        expect(Attribute::query()->count())->toBe(1)
            ->and(Attribute::query()->first()->name)->toBe('Color')
            ->and(Attribute::query()->first()->slug)->toBe('color');
    });

    it('auto generates slug from name', function (): void {
        $component = Livewire::test(AttributeForm::class)
            ->assertFormExists()
            ->fillForm([
                'name' => 'Product Size',
            ]);

        expect($component->get('data.slug'))->toBe('product-size');
    });

    it('validates required fields', function (): void {
        Livewire::test(AttributeForm::class)
            ->assertFormExists()
            ->fillForm()
            ->call('store')
            ->assertHasFormErrors(['name' => 'required', 'type' => 'required']);
    });

    it('validates unique slug', function (): void {
        Attribute::factory()->create(['slug' => 'color']);

        Livewire::test(AttributeForm::class)
            ->fillForm([
                'name' => 'Color',
                'slug' => 'color',
                'type' => FieldType::Select(),
            ])
            ->call('store')
            ->assertHasFormErrors(['slug' => 'unique']);
    });

    it('can edit existing attribute', function (): void {
        $this->user->givePermissionTo('edit_attributes');

        $attribute = Attribute::factory()->create([
            'name' => 'Old Name',
            'description' => 'Short description',
        ]);

        Livewire::test(AttributeForm::class, ['attributeId' => $attribute->id])
            ->fillForm([
                'name' => 'New Name',
                'type' => FieldType::Select(),
                'description' => 'Updated description',
            ])
            ->call('store')
            ->assertHasNoFormErrors();

        expect($attribute->refresh()->name)->toBe('New Name');
    });

    it('initializes form with attribute data on edit', function (): void {
        $this->user->givePermissionTo('edit_attributes');

        $attribute = Attribute::factory()->create([
            'name' => 'Size',
            'type' => FieldType::Select(),
        ]);

        $component = Livewire::test(AttributeForm::class, ['attributeId' => $attribute->id]);

        expect($component->get('data.name'))->toBe('Size')
            ->and($component->get('attribute')->id)->toBe($attribute->id);
    });

    it('sends notification on successful save', function (): void {
        Livewire::test(AttributeForm::class)
            ->fillForm([
                'name' => 'Material',
                'slug' => 'material',
                'type' => FieldType::Text(),
            ])
            ->call('store')
            ->assertNotified(__('shopper::pages/attributes.notifications.save'));
    });
})->group('livewire', 'attributes');
