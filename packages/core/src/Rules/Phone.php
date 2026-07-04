<?php

declare(strict_types=1);

namespace Shopper\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Shopper\Core\Models\Country;

final class Phone implements ValidationRule
{
    public function __construct(
        private readonly ?string $defaultRegion = null,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (blank($value) || ! is_string($value)) {
            return;
        }

        $phoneNumberUtil = PhoneNumberUtil::getInstance();

        try {
            $phoneNumber = $phoneNumberUtil->parse($value, $this->resolveRegion());
        } catch (NumberParseException) {
            $fail('shopper-core::validation.phone')->translate();

            return;
        }

        if (! $phoneNumberUtil->isValidNumber($phoneNumber)) {
            $fail('shopper-core::validation.phone')->translate();
        }
    }

    public static function defaultRegion(): ?string
    {
        $countryId = shopper_setting('country_id');

        if (! $countryId) {
            return null;
        }

        return Country::query()->find($countryId)?->cca2;
    }

    private function resolveRegion(): ?string
    {
        return $this->defaultRegion ?? self::defaultRegion();
    }
}
