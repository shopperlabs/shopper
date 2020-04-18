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
                Rule::unique(shopper_table('products'))->ignore($this->route()->parameter('product'))
            ],
            'brand_id' => 'sometimes|integer|nullable|exists:'.shopper_table('brands').',id',
        ];
    }
}
