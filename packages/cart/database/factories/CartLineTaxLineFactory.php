<?php

declare(strict_types=1);

namespace Shopper\Cart\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Cart\Models\CartLine;
use Shopper\Cart\Models\CartLineTaxLine;

/**
 * @extends Factory<CartLineTaxLine>
 */
class CartLineTaxLineFactory extends Factory
{
    protected $model = CartLineTaxLine::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cart_line_id' => CartLine::factory(),
            'code' => 'vat',
            'name' => 'VAT',
            'rate' => 20.0,
            'amount' => $this->faker->numberBetween(100, 5_000),
        ];
    }
}
