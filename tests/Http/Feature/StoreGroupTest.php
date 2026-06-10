<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Shopper\Core\Models\Contracts\Zone as ZoneContract;
use Shopper\Core\Models\Zone;
use Shopper\Http\Contracts\ZoneResolver;
use Shopper\Http\Facades\ShopperApi;
use Tests\Core\Stubs\User;

uses(Tests\Http\TestCase::class);

it('prefixes store routes and forces a JSON:API content type', function (): void {
    ShopperApi::store(function (): void {
        Route::get('/ping', fn () => response()->json(['ok' => true]));
    });

    $this->getJson('/store/ping')
        ->assertOk()
        ->assertHeader('Content-Type', 'application/vnd.api+json')
        ->assertJson(['ok' => true]);
});

it('binds the resolved zone onto the request', function (): void {
    $zone = Zone::factory()->make(['code' => 'eu']);

    app()->bind(ZoneResolver::class, fn (): ZoneResolver => new class($zone) implements ZoneResolver
    {
        public function __construct(private readonly Zone $zone) {}

        public function resolve(Request $request): ?ZoneContract
        {
            return $this->zone;
        }
    });

    ShopperApi::store(function (): void {
        Route::get('/zone', fn (Request $request) => response()->json([
            'code' => $request->attributes->get('shopper_zone')?->code,
        ]));
    });

    $this->getJson('/store/zone')
        ->assertOk()
        ->assertJson(['code' => 'eu']);
});

it('rejects unauthenticated requests on the authenticated group', function (): void {
    ShopperApi::authenticated(function (): void {
        Route::get('/me', fn () => response()->json(['ok' => true]));
    });

    $this->getJson('/store/me')
        ->assertUnauthorized()
        ->assertHeader('Content-Type', 'application/vnd.api+json')
        ->assertJsonPath('errors.0.status', '401');
});

it('binds the authenticated customer onto the request', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum');

    ShopperApi::authenticated(function (): void {
        Route::get('/me', fn (Request $request) => response()->json([
            'id' => $request->attributes->get('shopper_customer')?->getAuthIdentifier(),
        ]));
    });

    $this->getJson('/store/me')
        ->assertOk()
        ->assertJson(['id' => $user->getKey()]);
});
