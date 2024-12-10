<?php

declare(strict_types=1);

namespace Shopper\Actions\Store;

use Shopper\Core\Models\Discount;
use Shopper\Jobs\AttachedDiscountToCustomers;
use Shopper\Jobs\AttachedDiscountToProducts;

final readonly class SaveAndDispatchDiscountAction
{
    public function __invoke(
        array $values,
        ?int $discountId = null,
        array $productsIds = [],
        array $customersIds = []
    ): Discount {
        $discount = Discount::query()->updateOrCreate(
            attributes: ['id' => $discountId],
            values: $values,
        );

        AttachedDiscountToProducts::dispatch(
            data_get($values, 'apply_to'),
            $productsIds,
            $discount,
        );

        AttachedDiscountToCustomers::dispatch(
            data_get($values, 'eligibility'),
            $customersIds,
            $discount,
        );

        return $discount;
    }
}
