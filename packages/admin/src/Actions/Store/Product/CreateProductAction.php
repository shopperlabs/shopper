<?php

declare(strict_types=1);

namespace Shopper\Actions\Store\Product;

use Filament\Forms\Form;
use Illuminate\Support\Arr;
use Shopper\Actions\Store\InitialQuantityInventory;
use Shopper\Core\Events\Products\Created;
use Shopper\Core\Models\Product;
use Shopper\Core\Repositories\ProductRepository;
use Shopper\Feature;

final class CreateProductAction
{
    public function __invoke(Form $form): Product
    {
        $state = $form->getState();

        /** @var Product $product */
        $product = (new ProductRepository)->create(
            Arr::except($state, ['quantity', 'categories'])
        );

        $form->model($product)->saveRelationships();

        if (Feature::enabled('category')) {
            $categoriesIds = (array) data_get($state, 'categories');

            if (count($categoriesIds) > 0) {
                $product->categories()->sync($categoriesIds);
            }
        }

        $quantity = data_get($state, 'quantity');

        if ($quantity && (int) $quantity > 0) {
            app()->call(InitialQuantityInventory::class, [
                'quantity' => $quantity,
                'product' => $product,
            ]);
        }

        event(new Created($product));

        return $product;
    }
}
