<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Shopper\Core\Contracts\Priceable;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\Contracts\ProductVariant;
use Shopper\Core\Models\Price;

final class ResolvePurchasableAction
{
    public function execute(string $type, string $publicId, string $currencyCode): Priceable&Model
    {
        $purchasable = $type === 'variant'
            ? $this->findVariant($publicId)
            : $this->findProduct($publicId);

        if (! $purchasable instanceof Priceable) {
            throw ValidationException::withMessages([
                'purchasable_id' => __('This product is not available for purchase.'),
            ]);
        }

        if ($purchasable instanceof Product && $purchasable->canUseVariants()) {
            throw ValidationException::withMessages([
                'purchasable_id' => __('This product is sold through its variants, add one of them instead.'),
            ]);
        }

        if (! $purchasable->getPrice($currencyCode) instanceof Price) {
            throw ValidationException::withMessages([
                'purchasable_id' => __('This product has no price for the :currency currency.', ['currency' => $currencyCode]),
            ]);
        }

        return $purchasable;
    }

    /**
     * @return (Priceable&Model)|null
     */
    private function findProduct(string $publicId): ?Model
    {
        return resolve(Product::class)::query()
            ->publish()
            ->where('type', '<>', ProductType::External->value)
            ->wherePublicId($publicId)
            ->first();
    }

    /**
     * @return (Priceable&Model)|null
     */
    private function findVariant(string $publicId): ?Model
    {
        $variant = resolve(ProductVariant::class)::query()
            ->wherePublicId($publicId)
            ->first();

        if (! $variant instanceof Model || ! $variant instanceof Priceable) {
            return null;
        }

        $parentIsPublished = resolve(Product::class)::query()
            ->publish()
            ->whereKey($variant->getAttribute('product_id'))
            ->exists();

        return $parentIsPublished ? $variant : null;
    }
}
