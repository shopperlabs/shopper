<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Core\Enum\WebhookDeliveryStatus;
use Shopper\Core\Models\Traits\HasPublicId;

/**
 * @property-read int $id
 * @property-read ?string $public_id
 * @property-read int $webhook_event_id
 * @property-read int $webhook_subscription_id
 * @property-read int $attempt_number
 * @property-read WebhookDeliveryStatus $status
 * @property-read ?int $response_code
 * @property-read ?string $response_body
 * @property-read ?CarbonInterface $completed_at
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read WebhookEvent $event
 * @property-read WebhookSubscription $subscription
 */
class WebhookDelivery extends Model
{
    use HasPublicId;
    use Prunable;

    protected $guarded = [];

    public function getConnectionName(): ?string
    {
        return config('shopper.webhooks.database.connection') ?? parent::getConnectionName();
    }

    public function getTable(): string
    {
        return shopper_table('webhook_deliveries');
    }

    /**
     * Retention policy consumed by the scheduled `model:prune` command:
     * `succeeded` rows expire after `shopper.webhooks.prune.succeeded_after_days`
     * (default 7), every other status after `failed_after_days` (default 90).
     *
     * @return Builder<static>
     */
    public function prunable(): Builder
    {
        $succeededAfter = (int) config('shopper.webhooks.prune.succeeded_after_days', 7);
        $failedAfter = (int) config('shopper.webhooks.prune.failed_after_days', 90);

        return static::query()
            ->where(fn (Builder $query) => $query
                ->where('status', WebhookDeliveryStatus::Succeeded)
                ->where('created_at', '<', now()->subDays($succeededAfter)))
            ->orWhere(fn (Builder $query) => $query
                ->where('status', '<>', WebhookDeliveryStatus::Succeeded->value)
                ->where('created_at', '<', now()->subDays($failedAfter)));
    }

    /**
     * @return BelongsTo<WebhookEvent, $this>
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(WebhookEvent::class, 'webhook_event_id');
    }

    /**
     * @return BelongsTo<WebhookSubscription, $this>
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(WebhookSubscription::class, 'webhook_subscription_id');
    }

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'status' => WebhookDeliveryStatus::class,
        ];
    }
}
