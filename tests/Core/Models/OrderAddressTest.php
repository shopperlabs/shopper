<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Models\OrderAddress;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

describe(OrderAddress::class, function (): void {
    it('has full name accessor', function (): void {
        $address = OrderAddress::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        expect($address->full_name)->toBe('John Doe');
    });

    it('belongs to customer', function (): void {
        $customer = User::factory()->create();
        $address = OrderAddress::factory()->create(['customer_id' => $customer->id]);

        expect($address->customer->id)->toBe($customer->id);
    });

    it('has orders relationship', function (): void {
        $address = OrderAddress::factory()->create();

        expect($address->orders())->toBeInstanceOf(HasMany::class);
    });
})->group('order', 'models');
