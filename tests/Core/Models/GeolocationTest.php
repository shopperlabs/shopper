<?php

declare(strict_types=1);

use Shopper\Core\Models\Geolocation;
use Shopper\Core\Models\Order;
use Tests\Core\Stubs\User;

uses(Tests\Core\TestCase::class);

describe(Geolocation::class, function (): void {
    it('belongs to user', function (): void {
        $user = User::factory()->create();
        $order = Order::factory()->create();
        $geolocation = Geolocation::factory()->create([
            'user_id' => $user->id,
            'order_id' => $order->id,
        ]);

        expect($geolocation->user->id)->toBe($user->id);
    });

    it('belongs to order', function (): void {
        $user = User::factory()->create();
        $order = Order::factory()->create();
        $geolocation = Geolocation::factory()->create([
            'user_id' => $user->id,
            'order_id' => $order->id,
        ]);

        expect($geolocation->order->id)->toBe($order->id);
    });

    it('has json casts for ip data', function (): void {
        $user = User::factory()->create();
        $order = Order::factory()->create();
        $ipData = ['country' => 'US', 'city' => 'New York'];
        $geolocation = Geolocation::factory()->create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'ip_api' => $ipData,
            'extreme_ip_lookup' => $ipData,
        ]);

        expect($geolocation->ip_api)->toBeArray()
            ->and($geolocation->extreme_ip_lookup)->toBeArray();
    });
})->group('geolocation', 'models');
