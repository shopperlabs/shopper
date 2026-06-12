<?php

declare(strict_types=1);

use Laravel\Sanctum\Sanctum;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Models\Carrier;
use Shopper\Core\Models\CarrierOption;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\Zone;
use Shopper\Shipping\DataTransferObjects\ShippingRate;
use Shopper\Shipping\Facades\Shipping;
use Tests\Api\Stubs\FakeShippingDriver;
use Tests\Core\Stubs\User;

uses(Tests\Api\TestCase::class);

function createCartWithLine(Zone $zone, Product $product, int $quantity = 1): Cart
{
    $cart = Cart::query()->create([
        'currency_code' => 'USD',
        'zone_id' => $zone->id,
    ]);

    $cart->lines()->create([
        'purchasable_type' => $product->getMorphClass(),
        'purchasable_id' => $product->id,
        'quantity' => $quantity,
        'unit_price_amount' => 2500,
    ]);

    return $cart;
}

function addShippingAddress(Cart $cart, Country $country): void
{
    $cart->addresses()->create([
        'type' => AddressType::Shipping,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'address_1' => '1 Main Street',
        'city' => 'New York',
        'postal_code' => '10001',
        'country_id' => $country->id,
    ]);
}

function registerFakeCarrier(Zone $zone, FakeShippingDriver $driver): Carrier
{
    Shipping::extend('fake', fn (): FakeShippingDriver => $driver);

    $carrier = Carrier::factory()->create([
        'name' => 'Fake Carrier',
        'slug' => 'fake-carrier',
        'driver' => 'fake',
        'is_enabled' => true,
    ]);

    $zone->carriers()->attach($carrier);

    return $carrier;
}

beforeEach(function (): void {
    setupCurrencies();

    $this->country = Country::factory()->create(['cca2' => 'US']);

    $this->zone = Zone::factory()->create(['is_enabled' => true]);
    $this->zone->countries()->attach($this->country);

    $this->carrier = Carrier::factory()->create([
        'name' => 'Main Carrier',
        'slug' => 'main-carrier',
        'is_enabled' => true,
    ]);
    $this->zone->carriers()->attach($this->carrier);

    $this->option = CarrierOption::factory()->create([
        'name' => 'Standard',
        'price' => 700,
        'is_enabled' => true,
        'carrier_id' => $this->carrier->id,
        'zone_id' => $this->zone->id,
    ]);

    $this->product = Product::factory()->standard()->publish()->create([
        'weight_value' => 1.5,
        'weight_unit' => 'kg',
    ]);

    $this->cart = createCartWithLine($this->zone, $this->product);
});

it('lists manual zone options before any shipping address exists', function (): void {
    $this->getJson("/store/carts/{$this->cart->public_id}/shipping-options")
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.type', 'shipping-options')
        ->assertJsonPath('data.0.id', "main-carrier:{$this->option->public_id}")
        ->assertJsonPath('data.0.attributes.name', 'Standard')
        ->assertJsonPath('data.0.attributes.carrier_name', 'Main Carrier')
        ->assertJsonPath('data.0.attributes.carrier_code', 'main-carrier')
        ->assertJsonPath('data.0.attributes.amount', 700)
        ->assertJsonPath('data.0.attributes.currency', 'USD')
        ->assertJsonPath('data.0.attributes.price_type', 'flat')
        ->assertJsonPath('meta.warnings', []);
});

it('excludes disabled options and options from another zone', function (): void {
    CarrierOption::factory()->create([
        'name' => 'Disabled',
        'is_enabled' => false,
        'carrier_id' => $this->carrier->id,
        'zone_id' => $this->zone->id,
    ]);

    $otherZone = Zone::factory()->create(['is_enabled' => true]);
    CarrierOption::factory()->create([
        'name' => 'Elsewhere',
        'is_enabled' => true,
        'carrier_id' => $this->carrier->id,
        'zone_id' => $otherZone->id,
    ]);

    $this->getJson("/store/carts/{$this->cart->public_id}/shipping-options")
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.attributes.name', 'Standard');
});

it('excludes disabled carriers', function (): void {
    $disabledCarrier = Carrier::factory()->create([
        'name' => 'Disabled Carrier',
        'is_enabled' => false,
    ]);
    $this->zone->carriers()->attach($disabledCarrier);

    CarrierOption::factory()->create([
        'name' => 'Hidden',
        'is_enabled' => true,
        'carrier_id' => $disabledCarrier->id,
        'zone_id' => $this->zone->id,
    ]);

    $this->getJson("/store/carts/{$this->cart->public_id}/shipping-options")
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.attributes.name', 'Standard');
});

it('returns an empty list when the cart holds nothing shippable', function (): void {
    $virtualProduct = Product::factory()->virtual()->publish()->create();
    $cart = createCartWithLine($this->zone, $virtualProduct);

    $this->getJson("/store/carts/{$cart->public_id}/shipping-options")
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

it('quotes live carrier rates once the cart has a shipping address', function (): void {
    Inventory::factory()->create(['is_default' => true, 'country_id' => $this->country->id]);

    $driver = new FakeShippingDriver([
        new ShippingRate(
            serviceCode: 'express',
            serviceName: 'Fake Express',
            amount: 1295,
            currency: 'USD',
            carrierCode: 'fake-carrier',
            estimatedDays: '2',
        ),
    ]);
    registerFakeCarrier($this->zone, $driver);
    addShippingAddress($this->cart, $this->country);

    $this->getJson("/store/carts/{$this->cart->public_id}/shipping-options")
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.1.id', 'fake-carrier:express')
        ->assertJsonPath('data.1.attributes.name', 'Fake Express')
        ->assertJsonPath('data.1.attributes.carrier_name', 'Fake Carrier')
        ->assertJsonPath('data.1.attributes.amount', 1295)
        ->assertJsonPath('data.1.attributes.price_type', 'calculated')
        ->assertJsonPath('data.1.attributes.estimated_days', '2');

    expect($driver->calls)->toBe(1);
});

it('never calls carrier APIs while the cart has no shipping address', function (): void {
    Inventory::factory()->create(['is_default' => true, 'country_id' => $this->country->id]);

    $driver = new FakeShippingDriver;
    registerFakeCarrier($this->zone, $driver);

    $this->getJson("/store/carts/{$this->cart->public_id}/shipping-options")
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.attributes.price_type', 'flat');

    expect($driver->calls)->toBe(0);
});

it('keeps manual rates and warns when a carrier API is down', function (): void {
    Inventory::factory()->create(['is_default' => true, 'country_id' => $this->country->id]);

    $driver = new FakeShippingDriver(fails: true);
    registerFakeCarrier($this->zone, $driver);
    addShippingAddress($this->cart, $this->country);

    $response = $this->getJson("/store/carts/{$this->cart->public_id}/shipping-options")
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.attributes.name', 'Standard');

    expect($response->json('meta.warnings'))->toHaveCount(1)
        ->and($response->json('meta.warnings.0'))->toContain('Fake Carrier');
});

it('drops options priced in another currency than the cart and warns', function (): void {
    Inventory::factory()->create(['is_default' => true, 'country_id' => $this->country->id]);

    $driver = new FakeShippingDriver([
        new ShippingRate(
            serviceCode: 'eu-express',
            serviceName: 'Euro Express',
            amount: 1100,
            currency: 'EUR',
            carrierCode: 'fake-carrier',
        ),
    ]);
    registerFakeCarrier($this->zone, $driver);
    addShippingAddress($this->cart, $this->country);

    $response = $this->getJson("/store/carts/{$this->cart->public_id}/shipping-options")
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.attributes.currency', 'USD');

    expect($response->json('meta.warnings.0'))->toContain('EUR');
});

it('caches live carrier quotes until the cart content changes', function (): void {
    Inventory::factory()->create(['is_default' => true, 'country_id' => $this->country->id]);

    $driver = new FakeShippingDriver([
        new ShippingRate(
            serviceCode: 'express',
            serviceName: 'Fake Express',
            amount: 1295,
            currency: 'USD',
            carrierCode: 'fake-carrier',
        ),
    ]);
    registerFakeCarrier($this->zone, $driver);
    addShippingAddress($this->cart, $this->country);

    $url = "/store/carts/{$this->cart->public_id}/shipping-options";

    $this->getJson($url)->assertOk();
    $this->getJson($url)->assertOk();

    expect($driver->calls)->toBe(1);

    $this->cart->lines()->first()->update(['quantity' => 3]);

    $this->getJson($url)->assertOk();

    expect($driver->calls)->toBe(2);
});

it('rejects a cart that has no zone', function (): void {
    $cart = Cart::query()->create(['currency_code' => 'USD']);

    $cart->lines()->create([
        'purchasable_type' => $this->product->getMorphClass(),
        'purchasable_id' => $this->product->id,
        'quantity' => 1,
        'unit_price_amount' => 2500,
    ]);

    $this->getJson("/store/carts/{$cart->public_id}/shipping-options")
        ->assertUnprocessable();
});

it('returns 404 for an unknown cart', function (): void {
    $this->getJson('/store/carts/01JUNKNOWNCARTID0000000000/shipping-options')
        ->assertNotFound();
});

it('hides a customer cart from guests', function (): void {
    $customer = User::factory()->create();
    $cart = Cart::query()->create([
        'currency_code' => 'USD',
        'zone_id' => $this->zone->id,
        'customer_id' => $customer->id,
    ]);

    $this->getJson("/store/carts/{$cart->public_id}/shipping-options")
        ->assertNotFound();

    Sanctum::actingAs($customer, ['store']);

    $this->getJson("/store/carts/{$cart->public_id}/shipping-options")
        ->assertOk();
});
