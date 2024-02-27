<?php

declare(strict_types=1);

namespace Shopper\Core\Rules;

use Illuminate\Contracts\Validation\Rule;

class Phone implements Rule
{
    public function passes($attribute, $value): bool
    {
        return $this->isPhone($value);
    }

    public function message(): string
    {
        return __('Incorrect phone format for :attribute.');
    }

    protected function isPhone(string $value): bool
    {
        return $this->isE123($value) || $this->isE164($value) || $this->isNANP($value) || $this->isDigits($value);
    }

    protected function isDigits(string $value): bool
    {
        $conditions = [];
        $conditions[] = mb_strlen($value) >= 10;
        $conditions[] = mb_strlen($value) <= 16;
        $conditions[] = preg_match('/[^\\d]/i', $value) === 0;

        return (bool) array_product($conditions);
    }

    protected function isE123(string $value): bool
    {
        return preg_match('/^(?:\((\+?\d+)?\)|\+?\d+) ?\d*(-?\d{2,3} ?){0,4}$/', $value) === 1;
    }

    protected function isE164(string $value): bool
    {
        $conditions = [];
        $conditions[] = mb_strpos($value, '+') === 0;
        $conditions[] = mb_strlen($value) >= 9;
        $conditions[] = mb_strlen($value) <= 16;
        $conditions[] = preg_match('/[^\\d+]/i', $value) === 0;

        return (bool) array_product($conditions);
    }

    protected function isNANP(string $value): bool
    {
        $conditions = [];
        $conditions[] = preg_match('/^(?:\\+1|1)?\\s?-?\\(?\\d{3}\\)?(\\s|-)?\\d{3}-\\d{4}$/i', $value) > 0;

        return (bool) array_product($conditions);
    }
}
