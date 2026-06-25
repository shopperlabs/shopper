<?php

declare(strict_types=1);

namespace Shopper\Payment\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property-read string $driver
 * @property-read string $event_id
 * @property-read ?string $type
 * @property-read array<string, mixed>|null $payload
 * @property-read ?CarbonInterface $processed_at
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
class PaymentWebhookEvent extends Model
{
    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('payment_webhook_events');
    }

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'processed_at' => 'datetime',
        ];
    }
}
