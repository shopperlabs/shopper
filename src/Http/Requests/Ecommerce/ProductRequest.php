<?php

namespace Shopper\Framework\Http\Requests\Ecommerce;

use Illuminate\Validation\Rule;
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
            'name'  => 'bail|required',
            'sku'  => 'nullable|unique:'.shopper_table('products'),
            'barcode'  => 'nullable|unique:'.shopper_table('products'),
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
            'sku'  => [
                'sometimes',
                'nullable',
                Rule::unique(shopper_table('products'), 'sku')->ignore($this->route()->parameter('product'))
            ],
            'barcode'  => [
                'sometimes',
                'nullable',
                Rule::unique(shopper_table('products'), 'barcode')->ignore($this->route()->parameter('product'))
            ],
            'brand_id' => 'sometimes|integer|nullable|exists:'.shopper_table('brands').',id',
        ];
    }
}
