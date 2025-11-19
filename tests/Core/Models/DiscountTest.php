<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Models\Discount;

uses(Tests\TestCase::class);

describe(Discount::class, function (): void {
    it('checks if discount has reached limit', function (): void {
        $limited = Discount::factory()->create(['usage_limit' => 10, 'total_use' => 10]);
        $notLimited = Discount::factory()->create(['usage_limit' => 10, 'total_use' => 5]);
        $noLimit = Discount::factory()->create(['usage_limit' => null, 'total_use' => 100]);

        expect($limited->hasReachedLimit())->toBeTrue()
            ->and($notLimited->hasReachedLimit())->toBeFalse()
            ->and($noLimit->hasReachedLimit())->toBeFalse();
    });

    it('has items relationship', function (): void {
        $discount = Discount::factory()->create();

        expect($discount->items())->toBeInstanceOf(HasMany::class);
    });

    it('has zone relationship', function (): void {
        $discount = Discount::factory()->create();

        expect($discount->zone())->toBeInstanceOf(BelongsTo::class);
    });
})->group('discount', 'models');
