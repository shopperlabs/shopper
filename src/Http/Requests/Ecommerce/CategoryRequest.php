<?php

namespace Shopper\Framework\Http\Requests\Ecommerce;

use Shopper\Framework\Http\Requests\AbstractBaseRequest;

class CategoryRequest extends AbstractBaseRequest
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
            'parent_id' => 'integer|nullable|exists:'.shopper_table('categories').',id',
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
            'parent_id' => 'sometimes|integer|nullable|exists:'.shopper_table('categories').',id',
        ];
    }
}
