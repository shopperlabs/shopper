<?php

namespace Shopper\Framework\Http\Requests;

use Shopper\Framework\Http\Requests\AbstractBaseRequest;
use Shopper\Framework\Rules\Phone;

class CustomerRequest extends AbstractBaseRequest
{
    /**
     * Return store rules
     *
     * @return array
     */
    public function getStoreRules(): array
    {
        return [
            'first_name'  => 'required',
            'last_name'  => 'required',
            'email'  => 'required|unique:users',
            'password'  => 'required|confirmed|min:6',
            'phone_number' => ['nullable', new Phone()]
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
            'first_name'  => 'sometimes|required',
            'last_name'  => 'sometimes|required',
            'email'  => [
                'sometimes',
                'required',
                Rule::unique('users')->ignore($this->route()->parameter('customer'))
            ],
            'phone_number' => ['sometimes', 'nullable', new Phone()]
        ];
    }
}
