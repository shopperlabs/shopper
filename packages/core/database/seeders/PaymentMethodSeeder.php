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
            'title' => 'Cash',
            'slug' => 'cash',
            'is_enabled' => true,
        ]);
    }
}
