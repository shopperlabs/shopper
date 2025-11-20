<?php

declare(strict_types=1);

use Shopper\Core\Enum\AddressType;
use Shopper\Core\Exceptions\UndefinedEnumCaseError;
use Shopper\Core\Models\Address;
use Shopper\Core\Models\User;

uses(Tests\TestCase::class);

describe(Address::class, function (): void {
    it('has address types from enum', function (): void {
        $user = User::factory()->create();
        $shipping = Address::factory()->create(['type' => AddressType::Shipping, 'user_id' => $user->id]);
        $billing = Address::factory()->create(['type' => AddressType::Billing, 'user_id' => $user->id]);

        expect($shipping->type)->toBe(AddressType::Shipping)
            ->and($shipping->type->getLabel())->toBe(__('shopper-core::enum/address.shipping'))
            ->and($billing->type)->toBe(AddressType::Billing)
            ->and($billing->type->getLabel())->toBe(__('shopper-core::enum/address.billing'));
    });

    it('has full address', function (): void {
        /** @var User $user */
        $user = User::factory()->create();
        $address = Address::factory()->create([
            'user_id' => $user->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'street_address' => '123 Main St',
            'postal_code' => '12345',
            'city' => 'Springfield',
        ]);

        expect($address->first_name)->toBe('John')
            ->and($address->last_name)->toBe('Doe')
            ->and($address->full_name)->toContain('John')
            ->and($address->full_name)->toContain('Doe');
    });

    it('belongs to user', function (): void {
        $user = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);

        expect($address->user->id)->toBe($user->id);
    });

    it('can call enum cases as static methods', function (): void {
        expect(AddressType::Billing())->toBe('billing')
            ->and(AddressType::Shipping())->toBe('shipping');
    });

    it('throws exception when calling undefined enum case', function (): void {
        AddressType::Delivery();
    })->throws(
        UndefinedEnumCaseError::class,
        'Undefined constant Shopper\Core\Enum\AddressType::Delivery.'
    );
})->group('address', 'models');
