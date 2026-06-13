<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Models\Address;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderAddress;
use Shopper\Core\Models\OrderItem;
use Shopper\Core\Models\OrderShipping;
use Shopper\Core\Models\OrderShippingEvent;
use Shopper\Core\Models\PaymentMethod;
use Tests\Core\Stubs\User;

uses(Tests\Api\TestCase::class);

beforeEach(function (): void {
    $this->customer = User::factory()->create();
});

it('requires authentication on every account endpoint', function (): void {
    $this->getJson('/store/customers/me')->assertUnauthorized();
    $this->getJson('/store/customers/me/addresses')->assertUnauthorized();
    $this->getJson('/store/customers/me/orders')->assertUnauthorized();
});

it('shows and updates the authenticated customer profile', function (): void {
    Sanctum::actingAs($this->customer, ['store']);

    $response = $this->getJson('/store/customers/me')
        ->assertOk()
        ->assertJsonPath('data.type', 'customers')
        ->assertJsonPath('data.id', $this->customer->public_id)
        ->assertJsonPath('data.attributes.email', $this->customer->email);

    expect($response->json('data.attributes.avatar'))->toBeString()->not->toBeEmpty();

    $this->patchJson('/store/customers/me', [
        'first_name' => 'Updated',
        'phone_number' => '+33612345678',
        'opt_in' => true,
    ])->assertOk()
        ->assertJsonPath('data.attributes.first_name', 'Updated')
        ->assertJsonPath('data.attributes.phone_number', '+33612345678')
        ->assertJsonPath('data.attributes.opt_in', true);

    expect($this->customer->fresh()->email)->toBe($this->customer->email);
});

it('uploads a profile photo and replaces the previous one', function (): void {
    Storage::fake(config('shopper.media.storage.disk_name'));
    Sanctum::actingAs($this->customer, ['store']);

    $this->post('/store/customers/me/avatar', ['avatar' => UploadedFile::fake()->image('first.jpg')])
        ->assertOk()
        ->assertJsonPath('data.attributes.avatar_type', 'storage');

    $firstPath = $this->customer->fresh()->avatar_location;
    Storage::disk(config('shopper.media.storage.disk_name'))->assertExists($firstPath);

    $response = $this->post('/store/customers/me/avatar', ['avatar' => UploadedFile::fake()->image('second.png')])
        ->assertOk();

    $secondPath = $this->customer->fresh()->avatar_location;
    Storage::disk(config('shopper.media.storage.disk_name'))->assertMissing($firstPath);
    Storage::disk(config('shopper.media.storage.disk_name'))->assertExists($secondPath);
    expect($response->json('data.attributes.avatar'))->toContain($secondPath);

    $this->post('/store/customers/me/avatar', ['avatar' => UploadedFile::fake()->create('not-an-image.pdf', 50, 'application/pdf')])
        ->assertUnprocessable();
});

it('removes the profile photo and falls back to the generated avatar', function (): void {
    Storage::fake(config('shopper.media.storage.disk_name'));
    Sanctum::actingAs($this->customer, ['store']);

    $this->post('/store/customers/me/avatar', ['avatar' => UploadedFile::fake()->image('photo.jpg')])->assertOk();
    $path = $this->customer->fresh()->avatar_location;

    $this->deleteJson('/store/customers/me/avatar')
        ->assertOk()
        ->assertJsonPath('data.attributes.avatar_type', 'avatar_ui');

    Storage::disk(config('shopper.media.storage.disk_name'))->assertMissing($path);
    expect($this->customer->fresh()->avatar_location)->toBeNull();
});

it('manages the customer addresses with exclusive default flags', function (): void {
    Sanctum::actingAs($this->customer, ['store']);

    $first = $this->postJson('/store/customers/me/addresses', [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'street_address' => '12 rue de la Paix',
        'postal_code' => '75002',
        'city' => 'Paris',
        'country_code' => 'fr',
        'type' => 'shipping',
        'shipping_default' => true,
    ])->assertCreated()
        ->assertJsonPath('data.type', 'addresses')
        ->assertJsonPath('data.attributes.country_code', 'FR')
        ->assertJsonPath('data.attributes.shipping_default', true)
        ->json('data.id');

    $second = $this->postJson('/store/customers/me/addresses', [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'street_address' => '8 avenue des Champs',
        'postal_code' => '75008',
        'city' => 'Paris',
        'country_code' => 'FR',
        'type' => 'shipping',
        'shipping_default' => true,
    ])->assertCreated()->json('data.id');

    $ids = collect($this->getJson('/store/customers/me/addresses')->assertOk()->json('data'))->pluck('id');
    expect($ids)->toContain($first)->toContain($second);

    $defaults = Address::query()->where('user_id', $this->customer->id)->where('shipping_default', true)->get();
    expect($defaults)->toHaveCount(1)
        ->and($defaults->first()->public_id)->toBe($second);

    $this->patchJson("/store/customers/me/addresses/{$first}", ['city' => 'Lyon'])
        ->assertOk()
        ->assertJsonPath('data.attributes.city', 'Lyon');

    $this->deleteJson("/store/customers/me/addresses/{$first}")->assertNoContent();
    expect(Address::query()->where('public_id', $first)->exists())->toBeFalse();
});

it('never exposes another customer addresses', function (): void {
    $stranger = User::factory()->create();
    $country = Country::query()->where('cca2', 'FR')->firstOrFail();

    $foreign = Address::factory()->create([
        'user_id' => $stranger->id,
        'country_id' => $country->id,
        'type' => 'shipping',
    ]);

    Sanctum::actingAs($this->customer, ['store']);

    $ids = collect($this->getJson('/store/customers/me/addresses')->assertOk()->json('data'))->pluck('id');
    expect($ids)->not->toContain($foreign->public_id);

    $this->patchJson("/store/customers/me/addresses/{$foreign->public_id}", ['city' => 'Hacked'])
        ->assertNotFound();

    $this->deleteJson("/store/customers/me/addresses/{$foreign->public_id}")->assertNotFound();
});

it('lists only the customer orders with computed totals', function (): void {
    $order = Order::factory()->create(['customer_id' => $this->customer->id]);
    OrderItem::factory()->create([
        'order_id' => $order->id,
        'name' => 'Casque audio',
        'quantity' => 2,
        'unit_price_amount' => 1500,
        'discount_amount' => 500,
    ]);

    $foreign = Order::factory()->create(['customer_id' => User::factory()->create()->id]);

    Sanctum::actingAs($this->customer, ['store']);

    $response = $this->getJson('/store/customers/me/orders?include=items')->assertOk();

    $ids = collect($response->json('data'))->pluck('id');
    expect($ids)->toContain($order->public_id)->not->toContain($foreign->public_id);

    $row = collect($response->json('data'))->firstWhere('id', $order->public_id);
    expect($row['attributes']['total'])->toBe(2500)
        ->and($row['attributes']['number'])->toBe((string) $order->number)
        ->and($row['attributes']['status'])->toBe($order->status->value);

    expect(collect($response->json('included'))->pluck('type'))->toContain('order-items');
});

it('retrieves a customer order with its full account detail', function (): void {
    $order = Order::factory()->create([
        'customer_id' => $this->customer->id,
        'shipping_address_id' => OrderAddress::factory()->create(['city' => 'Paris'])->id,
        'billing_address_id' => OrderAddress::factory()->create(['city' => 'Lyon'])->id,
        'payment_method_id' => PaymentMethod::factory()->create(['title' => 'Card', 'driver' => 'manual'])->id,
    ]);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'quantity' => 2,
        'unit_price_amount' => 1500,
        'discount_amount' => 0,
    ]);

    $shipping = OrderShipping::factory()->create([
        'order_id' => $order->id,
        'tracking_number' => '1Z999',
        'status' => ShipmentStatus::InTransit,
    ]);
    OrderShippingEvent::factory()->create([
        'order_shipping_id' => $shipping->id,
        'status' => ShipmentStatus::InTransit,
        'location' => 'Roissy Hub',
    ]);

    Sanctum::actingAs($this->customer, ['store']);

    $response = $this->getJson(
        "/store/customers/me/orders/{$order->public_id}"
        .'?include=items,shipping_address,billing_address,payment_method,shippings,shippings.events'
    )
        ->assertOk()
        ->assertJsonPath('data.id', $order->public_id)
        ->assertJsonPath('data.attributes.total', 3000);

    $included = collect($response->json('included'));

    expect($included->pluck('type'))->toContain('order-items', 'order-addresses', 'payment-methods', 'order-shippings', 'order-shipping-events')
        ->and($included->where('type', 'order-addresses')->pluck('attributes.city')->all())->toContain('Paris', 'Lyon')
        ->and($included->firstWhere('type', 'payment-methods')['attributes']['title'])->toBe('Card');

    $shipment = $included->firstWhere('type', 'order-shippings');

    expect($shipment['attributes']['tracking_number'])->toBe('1Z999')
        ->and($shipment['attributes']['status'])->toBe(ShipmentStatus::InTransit->value)
        ->and($shipment['attributes'])->not->toHaveKey('events');

    $events = $included->where('type', 'order-shipping-events')->values();

    expect($events)->toHaveCount(1)
        ->and($events[0]['attributes']['location'])->toBe('Roissy Hub')
        ->and($events[0]['attributes']['status'])->toBe(ShipmentStatus::InTransit->value);
});

it('hides another customer order from the account detail endpoint', function (): void {
    $foreign = Order::factory()->create(['customer_id' => User::factory()->create()->id]);

    Sanctum::actingAs($this->customer, ['store']);

    $this->getJson("/store/customers/me/orders/{$foreign->public_id}")->assertNotFound();
});
