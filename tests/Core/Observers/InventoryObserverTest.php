<?php

declare(strict_types=1);

use Shopper\Core\Models\Inventory;

uses(Tests\Core\TestCase::class);

describe('InventoryObserver', function (): void {
    it('demotes other defaults when an inventory is updated to default', function (): void {
        $existing = Inventory::factory()->create(['is_default' => true]);
        $other = Inventory::factory()->create(['is_default' => false]);

        $other->update(['is_default' => true]);

        expect($existing->fresh()->is_default)->toBeFalse()
            ->and($other->fresh()->is_default)->toBeTrue();
    });

    it('is a no-op when the saved inventory is not default', function (): void {
        $existing = Inventory::factory()->create(['is_default' => true]);
        $other = Inventory::factory()->create(['is_default' => false]);

        $other->update(['name' => 'Updated warehouse']);

        expect($existing->fresh()->is_default)->toBeTrue()
            ->and($other->fresh()->is_default)->toBeFalse();
    });

    it('demotes multiple stale defaults in a single update', function (): void {
        $first = Inventory::factory()->create();
        $second = Inventory::factory()->create();

        $first->forceFill(['is_default' => true])->saveQuietly();
        $second->forceFill(['is_default' => true])->saveQuietly();

        $promoted = Inventory::factory()->create(['is_default' => false]);
        $promoted->update(['is_default' => true]);

        expect(Inventory::query()->where('is_default', true)->count())->toBe(1)
            ->and($promoted->fresh()->is_default)->toBeTrue();
    });
})->group('inventory', 'observers');
