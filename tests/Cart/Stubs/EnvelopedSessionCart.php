<?php

declare(strict_types=1);

namespace Tests\Cart\Stubs;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Shopper\Cart\Models\Cart;

final class EnvelopedSessionCart extends Cart
{
    protected function paymentSession(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): ?array => $value === null ? null : json_decode($value, true)['session'],
            set: fn (?array $value): ?string => $value === null ? null : json_encode(['session' => $value]),
        );
    }
}
