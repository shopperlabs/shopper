<?php

declare(strict_types=1);

use Shopper\Core\Models\Country;
use Shopper\Core\Models\Inventory;

uses(Tests\TestCase::class);

describe(Inventory::class, function (): void {
    it('has default scope', function (): void {
        Inventory::factory()->create(['is_default' => false]);
        $defaultInventory = Inventory::factory()->create(['is_default' => true]);

        $result = Inventory::default()->first();

        expect($result->id)->toBe($defaultInventory->id)
            ->and($result->is_default)->toBeTrue();
    });

    it('belongs to country', function (): void {
        $country = Country::factory()->create();
        $inventory = Inventory::factory()->create(['country_id' => $country->id]);

        expect($inventory->country->id)->toBe($country->id);
    });

    it('has histories relationship', function (): void {
        $inventory = Inventory::factory()->create();

        expect($inventory->histories())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
    });
})->group('inventory', 'models');
