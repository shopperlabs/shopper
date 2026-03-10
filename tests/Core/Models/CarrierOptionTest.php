<?php

declare(strict_types=1);

use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Zone;

uses(Tests\Core\TestCase::class);

describe(CarrierOption::class, function (): void {
    it('checks if carrier option is enabled', function (): void {
        $carrierOption = CarrierOption::factory()->create(['is_enabled' => true]);

        expect($carrierOption->isEnabled())->toBeTrue();
    });

    it('checks if carrier option is not enabled', function (): void {
        $carrierOption = CarrierOption::factory()->create(['is_enabled' => false]);

        expect($carrierOption->isEnabled())->toBeFalse();
    });

    it('has enabled scope', function (): void {
        CarrierOption::factory()->create(['is_enabled' => false]);
        $enabled = CarrierOption::factory()->create(['is_enabled' => true]);

        $result = CarrierOption::enabled()->where('id', $enabled->id)->first();

        expect($result->id)->toBe($enabled->id)
            ->and($result->is_enabled)->toBeTrue();
    });

    it('belongs to carrier', function (): void {
        $carrier = Carrier::factory()->create();
        $carrierOption = CarrierOption::factory()->create(['carrier_id' => $carrier->id]);

        expect($carrierOption->carrier)->toBeInstanceOf(Carrier::class)
            ->and($carrierOption->carrier->id)->toBe($carrier->id);
    });

    it('belongs to zone', function (): void {
        $zone = Zone::factory()->create();
        $carrierOption = CarrierOption::factory()->create(['zone_id' => $zone->id]);

        expect($carrierOption->zone)->toBeInstanceOf(Zone::class)
            ->and($carrierOption->zone->id)->toBe($zone->id);
    });

    it('casts is_enabled to boolean', function (): void {
        $carrierOption = CarrierOption::factory()->create(['is_enabled' => 1]);

        expect($carrierOption->is_enabled)->toBeTrue()
            ->and($carrierOption->is_enabled)->toBeBool();
    });

    it('casts metadata to array', function (): void {
        $metadata = ['key' => 'value', 'another_key' => 'another_value'];
        $carrierOption = CarrierOption::factory()->create(['metadata' => $metadata]);

        expect($carrierOption->metadata)->toBeArray()
            ->and($carrierOption->metadata)->toBe($metadata);
    });

    it('converts price accessor and mutator correctly', function (): void {
        $carrierOption = CarrierOption::factory()->create();
        $carrierOption->update(['price' => 10]);

        expect($carrierOption->fresh()->price)->toBe(10)
            ->and($carrierOption->fresh()->getAttributes()['price'])->toBe(1000);
    });
})->group('carrier', 'models');
