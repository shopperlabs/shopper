<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

interface Order
{
    public function setDefaultOrderStatus(): void;

    public function total(): float|int;

    public function canBeCancelled(): bool;

    public function isNotCancelled(): bool;

    public function isPending(): bool;

    public function isNew(): bool;

    public function isRegister(): bool;

    public function isShipped(): bool;

    public function isCompleted(): bool;

    public function isPaid(): bool;

    public function shippingAddress(): BelongsTo;

    public function billingAddress(): BelongsTo;

    public function customer(): BelongsTo;

    public function channel(): BelongsTo;

    public function paymentMethod(): BelongsTo;

    public function parent(): BelongsTo;

    public function children(): HasMany;

    public function zone(): BelongsTo;

    public function refund(): HasOne;

    public function items(): HasMany;

    public function shippingOption(): BelongsTo;
}
