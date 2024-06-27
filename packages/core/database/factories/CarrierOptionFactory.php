<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Core\Models\CarrierOption;

class CarrierOptionFactory extends Factory
{
    protected $model = CarrierOption::class;

    public function definition(): array
    {
        return [];
    }
}
