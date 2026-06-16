<?php

declare(strict_types=1);

namespace Shopper\Payment\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Payment\Enum\TransactionStatus;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Models\PaymentTransaction;

/**
 * @extends Factory<PaymentTransaction>
 */
class PaymentTransactionFactory extends Factory
{
    protected $model = PaymentTransaction::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'driver' => 'manual',
            'type' => TransactionType::Capture->value,
            'status' => TransactionStatus::Success->value,
            'amount' => $this->faker->numberBetween(1_000, 100_000),
            'currency_code' => 'USD',
        ];
    }

    public function refund(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => TransactionType::Refund->value,
        ]);
    }
}
