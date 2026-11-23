<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\Collection;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\Zone;

uses(Tests\Core\TestCase::class);

it('rewrites the 2.x class names of zone relations to their morph aliases', function (): void {
    $table = shopper_table('zone_has_relations');

    $zone = Zone::factory()->create();
    $country = Country::factory()->create();
    $carrier = Carrier::factory()->create();
    $paymentMethod = PaymentMethod::factory()->create();
    $collection = Collection::factory()->create();

    DB::table($table)->insert([
        ['zone_id' => $zone->id, 'zonable_type' => Country::class, 'zonable_id' => $country->id],
        ['zone_id' => $zone->id, 'zonable_type' => 'country', 'zonable_id' => $country->id],
        ['zone_id' => $zone->id, 'zonable_type' => Carrier::class, 'zonable_id' => $carrier->id],
        ['zone_id' => $zone->id, 'zonable_type' => PaymentMethod::class, 'zonable_id' => $paymentMethod->id],
        ['zone_id' => $zone->id, 'zonable_type' => 'collection', 'zonable_id' => $collection->id],
    ]);

    runZoneMorphAliasesMigration();

    $zone->refresh();

    expect(DB::table($table)->where('zone_id', $zone->id)->orderBy('zonable_type')->pluck('zonable_type')->all())
        ->toBe(['carrier', 'collection', 'country', 'payment_method'])
        ->and($zone->countries->modelKeys())->toBe([$country->id])
        ->and($zone->carriers->modelKeys())->toBe([$carrier->id])
        ->and($zone->paymentMethods->modelKeys())->toBe([$paymentMethod->id])
        ->and($zone->collections->modelKeys())->toBe([$collection->id]);
})->group('zone', 'database');

it('keeps the zone relations an admin saved on 3.x over the 2.x ones', function (): void {
    $table = shopper_table('zone_has_relations');

    $europe = Zone::factory()->create();
    $africa = Zone::factory()->create();
    $stripe = PaymentMethod::factory()->create();
    $cashOnDelivery = PaymentMethod::factory()->create();
    $france = Country::factory()->create();

    DB::table($table)->insert([
        ['zone_id' => $europe->id, 'zonable_type' => PaymentMethod::class, 'zonable_id' => $stripe->id],
        ['zone_id' => $europe->id, 'zonable_type' => PaymentMethod::class, 'zonable_id' => $cashOnDelivery->id],
        ['zone_id' => $europe->id, 'zonable_type' => 'payment_method', 'zonable_id' => $stripe->id],
        ['zone_id' => $africa->id, 'zonable_type' => Country::class, 'zonable_id' => $france->id],
        ['zone_id' => $europe->id, 'zonable_type' => 'country', 'zonable_id' => $france->id],
    ]);

    runZoneMorphAliasesMigration();

    expect($europe->refresh()->paymentMethods->modelKeys())->toBe([$stripe->id])
        ->and($europe->countries->modelKeys())->toBe([$france->id])
        ->and($africa->refresh()->countries->modelKeys())->toBe([])
        ->and(DB::table($table)->where('zonable_type', 'like', 'Shopper%')->exists())->toBeFalse();
})->group('zone', 'database');

function runZoneMorphAliasesMigration(): void
{
    $migration = require dirname(__DIR__, 3).'/packages/core/database/migrations/2026_11_23_000001_use_morph_aliases_for_zone_relations.php';
    $migration->up();
}
