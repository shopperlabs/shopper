<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\Zone;

uses(Tests\Core\TestCase::class);

describe(Carrier::class, function (): void {
    it('has enabled scope', function (): void {
        Carrier::factory()->create([
            'is_enabled' => false,
            'slug' => 'disabled-carrier',
        ]);
        $enabled = Carrier::factory()->create([
            'is_enabled' => true,
            'slug' => 'enabled-carrier',
        ]);

        $result = Carrier::enabled()->where('id', $enabled->id)->first();

        expect($result->id)->toBe($enabled->id)
            ->and($result->is_enabled)->toBeTrue();
    });

    it('has options relationship', function (): void {
        $carrier = Carrier::factory()->create(['slug' => 'test-carrier']);

        expect($carrier->options())->toBeInstanceOf(HasMany::class);
    });

    it('has zones relationship from HasZones trait', function (): void {
        $carrier = Carrier::factory()->create(['slug' => 'test-carrier']);
        $zone = Zone::factory()->create();

        $carrier->zones()->attach($zone);

        expect($carrier->zones())->toBeInstanceOf(MorphToMany::class)
            ->and($carrier->zones()->count())->toBe(1);
    });
})->group('carrier', 'models');
