<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface OrderRefund
{
    public function setDefaultOrderRefundStatus(): void;

    public function customer(): BelongsTo;

    public function order(): BelongsTo;
}
