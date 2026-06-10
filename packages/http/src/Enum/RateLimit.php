<?php

declare(strict_types=1);

namespace Shopper\Http\Enum;

enum RateLimit: string
{
    case Default = 'shopper-api';

    case Auth = 'shopper-api-auth';

    case Checkout = 'shopper-api-checkout';

    case Webhook = 'shopper-api-webhook';

    public function maxAttempts(): int
    {
        return (int) config('shopper.http.rate_limiters.'.$this->value, 60);
    }
}
