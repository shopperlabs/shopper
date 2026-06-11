<?php

declare(strict_types=1);

use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Zone;

uses(Tests\Api\TestCase::class);

it('lists countries and retrieves one by `cca2` or `cca3` code', function (): void {
    $france = Country::query()->where('cca2', 'FR')->firstOrFail();

    $ids = collect($this->getJson('/store/countries?filter[name]=France')->assertOk()->json('data'))->pluck('id');
    expect($ids)->toContain($france->public_id);

    $this->getJson('/store/countries/fr')
        ->assertOk()
        ->assertJsonPath('data.type', 'countries')
        ->assertJsonPath('data.id', $france->public_id)
        ->assertJsonPath('data.attributes.cca2', 'FR');

    $this->getJson('/store/countries/FRA')
        ->assertOk()
        ->assertJsonPath('data.id', $france->public_id);

    $this->getJson('/store/countries/XX')->assertNotFound();
});

it('filters countries by zone code and includes their zones on demand', function (): void {
    $zone = Zone::factory()->create(['name' => 'Europe', 'code' => 'eu', 'is_enabled' => true]);
    $germany = Country::query()->where('cca2', 'DE')->firstOrFail();
    $japan = Country::query()->where('cca2', 'JP')->firstOrFail();

    $zone->countries()->attach($germany->id);

    $ids = collect($this->getJson('/store/countries?filter[zone]=eu')->assertOk()->json('data'))->pluck('id');
    expect($ids)->toContain($germany->public_id)->and($ids)->not->toContain($japan->public_id);

    $this->getJson('/store/countries/DE?include=zones')
        ->assertOk()
        ->assertJsonPath('included.0.type', 'zones')
        ->assertJsonPath('included.0.id', $zone->public_id);
});

it('lists only enabled zones and retrieves one by code with its currency and countries', function (): void {
    $euro = Currency::query()->where('code', 'EUR')->firstOrFail();

    $enabled = Zone::factory()->create(['name' => 'Europe', 'code' => 'eu', 'is_enabled' => true, 'currency_id' => $euro->id]);
    $disabled = Zone::factory()->create(['name' => 'Hidden', 'code' => 'hidden', 'is_enabled' => false]);

    $enabled->countries()->attach(Country::query()->where('cca2', 'IT')->firstOrFail()->id);

    $ids = collect($this->getJson('/store/zones')->assertOk()->json('data'))->pluck('id');
    expect($ids)->toContain($enabled->public_id)->and($ids)->not->toContain($disabled->public_id);

    $response = $this->getJson('/store/zones/eu?include=currency,countries')
        ->assertOk()
        ->assertJsonPath('data.type', 'zones')
        ->assertJsonPath('data.id', $enabled->public_id)
        ->assertJsonPath('data.attributes.currency_code', 'EUR');

    $included = collect($response->json('included'));
    expect($included->pluck('type'))->toContain('currencies')->toContain('countries');

    $this->getJson('/store/zones/hidden')->assertNotFound();
});

it('lists only enabled currencies and retrieves one by code', function (): void {
    $pound = Currency::query()->where('code', 'GBP')->firstOrFail();
    Currency::factory()->create(['code' => 'ZZZ', 'name' => 'Disabled Coin', 'is_enabled' => false]);

    $ids = collect($this->getJson('/store/currencies?filter[code]=GBP')->assertOk()->json('data'))->pluck('id');
    expect($ids)->toContain($pound->public_id);

    expect($this->getJson('/store/currencies?filter[code]=ZZZ')->assertOk()->json('data'))->toBeEmpty();

    $this->getJson('/store/currencies/gbp')
        ->assertOk()
        ->assertJsonPath('data.type', 'currencies')
        ->assertJsonPath('data.id', $pound->public_id)
        ->assertJsonPath('data.attributes.symbol', '£');

    $this->getJson('/store/currencies/ZZZ')->assertNotFound();
});
