<?php

declare(strict_types=1);

namespace Shopper\Payment\Enum;

enum WebhookAction: string
{
    case Authorized = 'authorized';

    case Captured = 'captured';

    case Refunded = 'refunded';

    case Canceled = 'canceled';

    case Failed = 'failed';

    case RefundFailed = 'refund_failed';

    case Ignored = 'ignored';

    /**
     * The order in which the events of one payment are replayed when they
     * were journalized before their order existed. Providers do not promise
     * delivery order, and a refund can only ever follow a capture. A refund
     * that failed comes last: a provider takes back a refund it confirmed,
     * never the reverse.
     */
    public function precedence(): int
    {
        return match ($this) {
            self::Authorized => 1,
            self::Captured => 2,
            self::Failed => 3,
            self::Canceled => 4,
            self::Refunded => 5,
            self::RefundFailed => 6,
            self::Ignored => 7,
        };
    }
}
