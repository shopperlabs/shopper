<?php

declare(strict_types=1);

namespace Shopper\Framework\Rules;

use Illuminate\Contracts\Validation\Rule;

class RealEmailValidator implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     */
    public function passes($attribute, $value): bool
    {
        return (bool) preg_match('/^([a-z0-9\\+_\\-]+)(\\.[a-z0-9\\+_\\-]+)*@([a-z0-9\\-]+\\.)+[a-z]{2,6}$/ix', $value);
    }

    public function message(): string
    {
        return __('This Email is not a valid email address.');
    }
}
