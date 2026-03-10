<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Attribute;
use Shopper\Livewire\Pages\Attribute\Browse;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('browse_attributes');
    $this->actingAs($this->user);
});

describe(Browse::class, function (): void {
    it('can render attributes browse component', function (): void {
        Livewire::test(Browse::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.attributes.browse');
    });

    it('can list attributes in table', function (): void {
        Attribute::factory()->count(3)->create();

        Livewire::test(Browse::class)
            ->loadTable()
            ->assertCanSeeTableRecords(Attribute::limit(3)->get());
    });
})->group('livewire', 'attributes');
