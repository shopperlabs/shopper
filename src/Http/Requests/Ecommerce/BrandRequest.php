<?php

namespace Shopper\Framework\Http\Requests\Ecommerce;

use Shopper\Framework\Http\Requests\AbstractBaseRequest;

class BrandRequest extends AbstractBaseRequest
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
        ];
    }
}
