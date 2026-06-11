<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Shopper\Cart\Models\Cart;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductVariant;
use Shopper\Core\Models\TaxRate;
use Shopper\Core\Models\TaxZone;
use Tests\Core\Stubs\User;

uses(Tests\Api\TestCase::class);

function includedCartLines(TestResponse $response): Collection
{
    return collect($response->json('included'))->where('type', 'cart-lines')->values();
}

beforeEach(function (): void {
    setupCurrencies();

    $this->currency = Currency::query()->where('code', 'USD')->first();
    $this->inventory = Inventory::factory()->create();

    $this->product = Product::factory()->standard()->publish()->create();
    $this->product->prices()->create([
        'amount' => 2500,
        'currency_id' => $this->currency->id,
    ]);
    $this->product->mutateStock($this->inventory->id, 50);
});

it('creates a guest cart with the default currency', function (): void {
    $response = $this->postJson('/store/carts')
        ->assertCreated()
        ->assertJsonPath('data.type', 'carts')
        ->assertJsonPath('data.attributes.currency_code', 'USD')
        ->assertJsonPath('data.attributes.total', 0)
        ->assertJsonPath('data.attributes.lines_count', 0);

    $cart = Cart::query()->where('public_id', $response->json('data.id'))->first();

    expect($cart)->not->toBeNull()
        ->and($cart->customer_id)->toBeNull();
});

it('attaches the cart to the authenticated customer', function (): void {
    $customer = User::factory()->create();
    Sanctum::actingAs($customer, ['store']);

    $cartId = $this->postJson('/store/carts', ['metadata' => ['source' => 'mobile']])
        ->assertCreated()
        ->assertJsonPath('data.attributes.metadata.source', 'mobile')
        ->json('data.id');

    expect(Cart::query()->where('public_id', $cartId)->value('customer_id'))->toBe($customer->id);
});

it('rejects a currency the shop does not sell in', function (): void {
    $this->postJson('/store/carts', ['currency_code' => 'XXX'])->assertUnprocessable();
});

it('retrieves a cart by its public id', function (): void {
    $cart = Cart::query()->create(['currency_code' => 'USD']);

    $this->getJson('/store/carts/'.$cart->public_id)
        ->assertOk()
        ->assertJsonPath('data.id', $cart->public_id)
        ->assertJsonPath('data.attributes.currency_code', 'USD');

    $this->getJson('/store/carts/01JUNKNOWNCARTIDENTIFIER00')->assertNotFound();
});

it('hides a customer cart from guests and other customers', function (): void {
    $owner = User::factory()->create();
    $cart = Cart::query()->create(['currency_code' => 'USD', 'customer_id' => $owner->id]);

    $this->getJson('/store/carts/'.$cart->public_id)->assertNotFound();

    Sanctum::actingAs(User::factory()->create(), ['store']);
    $this->getJson('/store/carts/'.$cart->public_id)->assertNotFound();

    Sanctum::actingAs($owner, ['store']);
    $this->getJson('/store/carts/'.$cart->public_id)->assertOk();
});

it('keeps lines out of the payload until the client includes them', function (): void {
    $cartId = $this->postJson('/store/carts')->json('data.id');

    $this->postJson("/store/carts/{$cartId}/lines", [
        'purchasable_type' => 'product',
        'purchasable_id' => $this->product->public_id,
    ])->assertOk();

    $summary = $this->getJson("/store/carts/{$cartId}")
        ->assertOk()
        ->assertJsonPath('data.attributes.lines_count', 1)
        ->assertJsonPath('data.attributes.subtotal', 2500);

    expect($summary->json('included'))->toBeNull()
        ->and($summary->json('data.relationships'))->toBeNull();
});

it('adds a product to the cart and computes totals', function (): void {
    $cartId = $this->postJson('/store/carts')->json('data.id');

    $response = $this->postJson("/store/carts/{$cartId}/lines?include=lines", [
        'purchasable_type' => 'product',
        'purchasable_id' => $this->product->public_id,
        'quantity' => 2,
    ])->assertOk()
        ->assertJsonPath('data.attributes.subtotal', 5000)
        ->assertJsonPath('data.attributes.total', 5000)
        ->assertJsonPath('data.attributes.lines_count', 1)
        ->assertJsonPath('data.attributes.lines_quantity', 2);

    $line = includedCartLines($response)->sole();

    expect($line['attributes']['quantity'])->toBe(2)
        ->and($line['attributes']['unit_price_amount'])->toBe(2500)
        ->and($line['attributes']['subtotal'])->toBe(5000)
        ->and($line['attributes']['purchasable_type'])->toBe('product');
});

it('expands the purchasable of each line on demand', function (): void {
    $cartId = $this->postJson('/store/carts')->json('data.id');

    $response = $this->postJson("/store/carts/{$cartId}/lines?include=lines.purchasable", [
        'purchasable_type' => 'product',
        'purchasable_id' => $this->product->public_id,
    ])->assertOk();

    $products = collect($response->json('included'))->where('type', 'products')->values();

    expect($products->sole()['id'])->toBe($this->product->public_id);
});

it('merges duplicate purchasables into a single line', function (): void {
    $cartId = $this->postJson('/store/carts')->json('data.id');
    $payload = ['purchasable_type' => 'product', 'purchasable_id' => $this->product->public_id];

    $this->postJson("/store/carts/{$cartId}/lines", [...$payload, 'quantity' => 1])->assertOk();

    $response = $this->postJson("/store/carts/{$cartId}/lines?include=lines", [...$payload, 'quantity' => 2])
        ->assertOk()
        ->assertJsonPath('data.attributes.lines_count', 1)
        ->assertJsonPath('data.attributes.lines_quantity', 3);

    expect(includedCartLines($response)->sole()['attributes']['quantity'])->toBe(3);
});

it('adds a variant to the cart', function (): void {
    $variant = ProductVariant::factory()->create(['product_id' => $this->product->id]);
    $variant->prices()->create([
        'amount' => 3000,
        'currency_id' => $this->currency->id,
    ]);
    $variant->mutateStock($this->inventory->id, 20);

    $cartId = $this->postJson('/store/carts')->json('data.id');

    $response = $this->postJson("/store/carts/{$cartId}/lines?include=lines.purchasable", [
        'purchasable_type' => 'variant',
        'purchasable_id' => $variant->public_id,
    ])->assertOk();

    $line = includedCartLines($response)->sole();
    $variants = collect($response->json('included'))->where('type', 'variants')->values();

    expect($line['attributes']['unit_price_amount'])->toBe(3000)
        ->and($line['attributes']['purchasable_type'])->toBe('variant')
        ->and($variants->sole()['id'])->toBe($variant->public_id);
});

it('rejects purchasables a storefront cannot sell', function (): void {
    $cartId = $this->postJson('/store/carts')->json('data.id');

    $draft = Product::factory()->standard()->create(['is_visible' => false]);
    $external = Product::factory()->external()->publish()->create();
    $parent = Product::factory()->publish()->create(['type' => ProductType::Variant]);
    $unpriced = Product::factory()->standard()->publish()->create();

    foreach ([
        '01JUNKNOWNPURCHASABLEID000',
        $draft->public_id,
        $external->public_id,
        $parent->public_id,
        $unpriced->public_id,
    ] as $publicId) {
        $this->postJson("/store/carts/{$cartId}/lines", [
            'purchasable_type' => 'product',
            'purchasable_id' => $publicId,
        ])->assertUnprocessable();
    }
});

it('returns a validation error when stock is insufficient', function (): void {
    $cartId = $this->postJson('/store/carts')->json('data.id');

    $this->postJson("/store/carts/{$cartId}/lines", [
        'purchasable_type' => 'product',
        'purchasable_id' => $this->product->public_id,
        'quantity' => 100,
    ])->assertUnprocessable();
});

it('updates the quantity of a line and removes it', function (): void {
    $cartId = $this->postJson('/store/carts')->json('data.id');

    $response = $this->postJson("/store/carts/{$cartId}/lines?include=lines", [
        'purchasable_type' => 'product',
        'purchasable_id' => $this->product->public_id,
    ]);

    $lineId = includedCartLines($response)->sole()['id'];

    $updated = $this->patchJson("/store/carts/{$cartId}/lines/{$lineId}?include=lines", ['quantity' => 3])
        ->assertOk()
        ->assertJsonPath('data.attributes.subtotal', 7500);

    expect(includedCartLines($updated)->sole()['attributes']['quantity'])->toBe(3);

    $this->deleteJson("/store/carts/{$cartId}/lines/{$lineId}")
        ->assertOk()
        ->assertJsonPath('data.attributes.lines_count', 0)
        ->assertJsonPath('data.attributes.subtotal', 0);

    $this->patchJson("/store/carts/{$cartId}/lines/{$lineId}", ['quantity' => 1])->assertNotFound();
});

it('returns a conflict when mutating a completed cart', function (): void {
    $cart = Cart::query()->create(['currency_code' => 'USD', 'completed_at' => now()]);

    $this->postJson("/store/carts/{$cart->public_id}/lines", [
        'purchasable_type' => 'product',
        'purchasable_id' => $this->product->public_id,
    ])->assertConflict();
});

it('exposes the cart addresses as an include', function (): void {
    $country = Country::query()->where('cca2', 'US')->first()
        ?? Country::factory()->create(['cca2' => 'US', 'name' => 'United States']);

    $cart = Cart::query()->create(['currency_code' => 'USD']);
    $cart->addresses()->create([
        'type' => AddressType::Shipping,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'address_1' => '123 Main St',
        'city' => 'New York',
        'postal_code' => '10001',
        'country_id' => $country->id,
    ]);

    $response = $this->getJson("/store/carts/{$cart->public_id}?include=addresses")->assertOk();

    $address = collect($response->json('included'))->where('type', 'cart-addresses')->sole();

    expect($address['attributes']['type'])->toBe('shipping')
        ->and($address['attributes']['country_code'])->toBe('US')
        ->and($address['attributes']['full_name'])->toBe('John Doe');
});

it('exposes discounts and taxes computed by the cart pipelines', function (): void {
    Discount::factory()->create([
        'code' => 'SAVE20',
        'is_active' => true,
        'type' => DiscountType::Percentage,
        'value' => 20,
        'apply_to' => DiscountApplyTo::Order,
        'eligibility' => DiscountEligibility::Everyone,
        'min_required' => DiscountRequirement::None,
        'start_at' => now()->subDay(),
        'end_at' => now()->addMonth(),
    ]);

    $country = Country::query()->where('cca2', 'US')->first()
        ?? Country::factory()->create(['cca2' => 'US', 'name' => 'United States']);

    $taxZone = TaxZone::factory()->create([
        'country_id' => $country->id,
        'is_tax_inclusive' => false,
    ]);

    TaxRate::factory()->create([
        'tax_zone_id' => $taxZone->id,
        'rate' => 10.00,
        'is_default' => true,
    ]);

    $cart = Cart::query()->create(['currency_code' => 'USD', 'coupon_code' => 'SAVE20']);
    $cart->addresses()->create([
        'type' => AddressType::Shipping,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'address_1' => '123 Main St',
        'city' => 'New York',
        'postal_code' => '10001',
        'country_id' => $country->id,
    ]);

    $response = $this->postJson("/store/carts/{$cart->public_id}/lines?include=lines", [
        'purchasable_type' => 'product',
        'purchasable_id' => $this->product->public_id,
        'quantity' => 2,
    ])->assertOk()
        ->assertJsonPath('data.attributes.subtotal', 5000)
        ->assertJsonPath('data.attributes.discount_total', 1000)
        ->assertJsonPath('data.attributes.tax_total', 400)
        ->assertJsonPath('data.attributes.total', 4400);

    $line = includedCartLines($response)->sole();

    expect($line['attributes']['discount_total'])->toBe(1000)
        ->and($line['attributes']['tax_total'])->toBe(400)
        ->and($line['attributes']['adjustments'])->toBe([['amount' => 1000, 'code' => 'SAVE20']])
        ->and($line['attributes']['tax_lines'][0]['rate'])->toEqual(10)
        ->and($line['attributes']['tax_lines'][0]['amount'])->toBe(400);
});
