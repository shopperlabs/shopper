<?php

namespace Shopper\Framework\Http\Requests;

class ShopRequest extends AbstractBaseRequest
{
    /**
     * @return bool
     */
    public function wantsJson()
    {
        return true;
    }

    /**
     * Rules for creating a new resource
     *
     * @var array
     */
    public $storeRules = [
        'name' => 'required|max:255|unique:shops',
        'email' => 'required|max:255|unique:shops',
        'phone_number' => 'required',
        'size_id'   => 'required|integer'
    ];
    /**
     * Rules for updating a resource
     *
     * @var array
     */
    public $updateRules = [
        'name' => 'sometimes|required|max:255',
        'code' => 'sometimes|required|max:255',
        'size_id' => 'sometimes|required|integer'
    ];
}
