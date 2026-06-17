<?php

declare(strict_types=1);

use Shopper\Core\Enum\DiscountStatus;
use Shopper\Core\Models\Discount;

uses(Tests\Core\TestCase::class);

it('reports `Active` for an enabled discount inside its window and limit', function (): void {
    $discount = Discount::factory()->create([
        'is_active' => true,
        'start_at' => now()->subDay(),
        'end_at' => now()->addMonth(),
        'usage_limit' => null,
    ]);

    expect($discount->status)->toBe(DiscountStatus::Active);
});

it('reports `Disabled` when the discount is not active', function (): void {
    $discount = Discount::factory()->create([
        'is_active' => false,
        'start_at' => now()->subDay(),
        'end_at' => now()->addMonth(),
    ]);

    expect($discount->status)->toBe(DiscountStatus::Disabled);
});

it('reports `Expired` when `end_at` is in the past', function (): void {
    $discount = Discount::factory()->create([
        'is_active' => true,
        'start_at' => now()->subWeek(),
        'end_at' => now()->subDay(),
    ]);

    expect($discount->status)->toBe(DiscountStatus::Expired);
});

it('reports `LimitReached` when the usage limit is hit', function (): void {
    $discount = Discount::factory()->create([
        'is_active' => true,
        'start_at' => now()->subDay(),
        'end_at' => now()->addMonth(),
        'usage_limit' => 5,
        'total_use' => 5,
    ]);

    expect($discount->status)->toBe(DiscountStatus::LimitReached);
});

it('reports `Scheduled` when `start_at` is in the future', function (): void {
    $discount = Discount::factory()->create([
        'is_active' => true,
        'start_at' => now()->addWeek(),
        'end_at' => now()->addMonth(),
        'usage_limit' => null,
    ]);

    expect($discount->status)->toBe(DiscountStatus::Scheduled);
});

it('reports `Draft` for an unsaved discount', function (): void {
    expect((new Discount)->status)->toBe(DiscountStatus::Draft);
});
