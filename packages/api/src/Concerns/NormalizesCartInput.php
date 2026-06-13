<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Closure;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;

trait NormalizesCartInput
{
    protected function prepareForValidation(): void
    {
        if ($this->filled('currency_code')) {
            $this->merge(['currency_code' => mb_strtoupper((string) $this->string('currency_code'))]);
        }

        if ($this->filled('email')) {
            $this->merge(['email' => mb_strtolower((string) $this->string('email'))]);
        }
    }

    /**
     * The currency must exist, be enabled, and be one of the currencies the
     * shop is configured to sell in: an enabled-but-not-configured currency
     * would price a cart the storefront cannot check out.
     */
    protected function currencyExistsRule(): Exists
    {
        return Rule::exists(shopper_table('currencies'), 'code')
            ->where('is_enabled', true)
            ->whereIn('id', (array) shopper_setting('currencies'));
    }

    /**
     * @return array<int, string|Closure>
     */
    protected function metadataRules(): array
    {
        return [
            'array',
            'max:50',
            function (string $attribute, mixed $value, Closure $fail): void {
                if (mb_strlen((string) json_encode($value)) > 4096) {
                    $fail(__('shopper-api::messages.cart.metadata_too_large'));
                }
            },
        ];
    }
}
