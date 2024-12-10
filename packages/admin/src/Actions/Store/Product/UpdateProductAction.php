<?php

declare(strict_types=1);

namespace Shopper\Actions\Store\Product;

use Filament\Forms\Form;
use Illuminate\Support\Arr;
use Shopper\Core\Events\Products\Updated;
use Shopper\Core\Models\Product;
use Shopper\Feature;

final class UpdateProductAction
{
    public function __invoke(Form $form, Product $product): Product
    {
        $state = $form->getState();

        $product->update(Arr::except($state, ['categories']));

        if (Feature::enabled('category')) {
            $categoriesIds = (array) data_get($state, 'categories');

            if (count($categoriesIds) > 0) {
                $product->categories()->sync($categoriesIds);
            }
        }

        $product->refresh();

        event(new Updated($product));

        return $product;
    }
}
