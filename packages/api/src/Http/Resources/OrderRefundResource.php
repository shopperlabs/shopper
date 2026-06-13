<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Core\Models\OrderRefund;

/**
 * @mixin OrderRefund
 */
final class OrderRefundResource extends JsonApiResource
{
    public function toType(Request $request): string
    {
        return 'order-refunds';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'status' => $this->status->value,
            'amount' => $this->refund_amount,
            'currency' => $this->currency,
            'reason' => $this->refund_reason,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
