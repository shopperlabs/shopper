<?php

namespace Shopper\Framework\Http\Requests\Ecommerce;

use Shopper\Framework\Http\Requests\AbstractBaseRequest;

class ProductRequest extends AbstractBaseRequest
{
    /**
     * Return store rules
     *
     * @return array
     */
    public function getStoreRules(): array
    {
        return [
            'name'  => 'required',
            'sku'  => 'unique:'.shopper_table('products').',sku',
            'brand_id' => 'integer|nullable|exists:'.shopper_table('brands').',id',
        ];
    }

    /**
     * Return update rules.
     *
     * @return array
     */
    public function getUpdateRules() : array
    {
        return [
            'name'  => 'sometimes|required',
            'sku'  => 'sometimes|unique:'.shopper_table('products').',sku',
            'brand_id' => 'sometimes|integer|nullable|exists:'.shopper_table('categories').',id',
        ];
    }
}
