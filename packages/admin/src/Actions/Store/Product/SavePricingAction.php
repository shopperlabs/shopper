<?php

declare(strict_types=1);

namespace Shopper\Actions\Store\Product;

use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Models\Price;

final class SavePricingAction
{
    public function __invoke(array $pricing, Model $model): void
    {
        foreach ($pricing as $key => $price) {
            $amount = data_get($price, 'amount');

            if ($amount) {
                Price::query()->updateOrCreate(
                    attributes: [
                        'currency_id' => $key,
                        'priceable_id' => $model->id, // @phpstan-ignore-line
                    ],
                    values: array_merge($price, [
                        'priceable_id' => $model->id, // @phpstan-ignore-line
                        'priceable_type' => $model->getMorphClass(),
                    ])
                );
            }
        }
    }
}
