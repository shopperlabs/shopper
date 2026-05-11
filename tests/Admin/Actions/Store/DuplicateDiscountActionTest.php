<?php

declare(strict_types=1);

use Shopper\Actions\Store\DuplicateDiscountAction;
use Shopper\Core\Enum\DiscountCondition;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\DiscountDetail;

uses(Tests\Admin\TestCase::class);

describe(DuplicateDiscountAction::class, function (): void {
    it('clones a discount with the _COPY suffix and resets the usage counter', function (): void {
        $original = Discount::factory()->create([
            'code' => 'SUMMER',
            'is_active' => true,
            'total_use' => 12,
        ]);

        $clone = (new DuplicateDiscountAction)($original);

        expect($clone)->toBeInstanceOf(Discount::class)
            ->and($clone->id)->not->toBe($original->id)
            ->and($clone->code)->toBe('SUMMER_COPY')
            ->and($clone->is_active)->toBeFalse()
            ->and($clone->total_use)->toBe(0);
    });

    it('appends an incremental suffix when _COPY already exists', function (): void {
        Discount::factory()->create(['code' => 'SUMMER']);
        Discount::factory()->create(['code' => 'SUMMER_COPY']);
        Discount::factory()->create(['code' => 'SUMMER_COPY_2']);

        $original = Discount::query()->where('code', 'SUMMER')->first();

        $clone = (new DuplicateDiscountAction)($original);

        expect($clone->code)->toBe('SUMMER_COPY_3');
    });

    it('clones every discount detail item attached to the original', function (): void {
        $original = Discount::factory()->create(['code' => 'WITH_ITEMS']);

        DiscountDetail::factory()->count(3)->create([
            'discount_id' => $original->id,
            'condition' => DiscountCondition::ApplyTo,
        ]);

        $clone = (new DuplicateDiscountAction)($original);

        expect($clone->items()->count())->toBe(3)
            ->and($original->items()->count())->toBe(3);
    });
})->group('actions', 'discounts');
