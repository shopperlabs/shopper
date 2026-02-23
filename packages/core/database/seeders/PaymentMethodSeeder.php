<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Shopper\Core\Models\PaymentMethod;

final class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        PaymentMethod::query()->create([
            'title' => 'Cash on delivery',
            'slug' => 'cod',
            'is_enabled' => true,
            'driver' => 'manual',
        ]);
    }
}
