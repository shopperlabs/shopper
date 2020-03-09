<?php

namespace Shopper\Framework\Http\Requests;

class LoginRequest extends AbstractBaseRequest
{
    /**
     * @return bool
     */
    public function wantsJson()
    {
        return true;
    }
}
