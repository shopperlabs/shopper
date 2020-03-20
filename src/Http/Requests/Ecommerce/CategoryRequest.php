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
            'parent_id' => 'integer|nullable|exists:id'.shopper_table('categories'),
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
            'parent_id' => 'sometimes|integer|nullable|exists:id'.shopper_table('categories'),
        ];
    }
}
