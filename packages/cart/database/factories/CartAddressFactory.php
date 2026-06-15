<?php

declare(strict_types=1);

namespace Shopper\Cart\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartAddress;
use Shopper\Core\Enum\AddressType;

/**
 * @extends Factory<CartAddress>
 */
class CartAddressFactory extends Factory
{
    protected $model = CartAddress::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cart_id' => Cart::factory(),
            'type' => AddressType::Shipping,
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'address_1' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
        ];
    }

    public function billing(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => AddressType::Billing,
        ]);
    }
}
