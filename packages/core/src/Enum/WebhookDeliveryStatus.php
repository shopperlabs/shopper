<?php

declare(strict_types=1);

namespace Shopper\Core\Enum;

use Shopper\Core\Contracts\HasColor;
use Shopper\Core\Contracts\HasLabel;
use Shopper\Core\Traits\ArrayableEnum;
use Shopper\Core\Traits\HasEnumStaticMethods;

enum WebhookDeliveryStatus: string implements HasColor, HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case Pending = 'pending';

    case Succeeded = 'succeeded';

    case Failed = 'failed';

    case Rejected = 'rejected';

    case DispatchFailed = 'dispatch_failed';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => __('shopper-core::enum/webhook.pending'),
            self::Succeeded => __('shopper-core::enum/webhook.succeeded'),
            self::Failed => __('shopper-core::enum/webhook.failed'),
            self::Rejected => __('shopper-core::enum/webhook.rejected'),
            self::DispatchFailed => __('shopper-core::enum/webhook.dispatch_failed'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Succeeded => 'success',
            self::Failed, self::Rejected, self::DispatchFailed => 'danger',
        };
    }
}
