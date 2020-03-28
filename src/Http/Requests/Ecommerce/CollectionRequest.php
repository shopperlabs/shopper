<?php

namespace Shopper\Framework\Http\Requests\Ecommerce;

use Shopper\Framework\Http\Requests\AbstractBaseRequest;

class CollectionRequest extends AbstractBaseRequest
{
    /**
     * Return store rules
     *
     * @return array
     */
    public function getStoreRules(): array
    {
        return [
            'name' => 'required',
            'type' => 'required|string',
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
            'name' => 'sometimes|required',
            'type' => 'sometimes|required|string',
        ];
    }
}
