<?php

declare(strict_types=1);

namespace Shopper\Payment\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Shopper\Payment\Database\Factories\PaymentWebhookEventFactory;
use Shopper\Payment\DataTransferObjects\WebhookResult;
use Shopper\Payment\Enum\WebhookAction;

/**
 * @property-read int $id
 * @property-read string $driver
 * @property-read string $event_id
 * @property-read ?WebhookAction $type
 * @property-read ?string $reference
 * @property-read array<string, mixed>|null $payload
 * @property-read ?CarbonInterface $processed_at
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
class PaymentWebhookEvent extends Model
{
    /** @use HasFactory<PaymentWebhookEventFactory> */
    use HasFactory;

    use Prunable;

    protected $guarded = [];

    /**
     * Journalize a delivery exactly once. insertOrIgnore keeps a redelivery
     * from raising the failed INSERT that would abort a surrounding Postgres
     * transaction; nothing inserted means the event is already on file.
     */
    public static function journal(string $driver, WebhookResult $result): ?static
    {
        $now = now();

        $inserted = static::query()->insertOrIgnore([
            'driver' => $driver,
            'event_id' => $result->eventId,
            'type' => $result->action->value,
            'reference' => $result->reference,
            'payload' => json_encode($result->toArray()),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        if ($inserted === 0) {
            return null;
        }

        return static::query()
            ->where('driver', $driver)
            ->where('event_id', $result->eventId)
            ->firstOrFail();
    }

    public function getTable(): string
    {
        return shopper_table('payment_webhook_events');
    }

    /**
     * Take the event for application. The conditional update succeeds for
     * exactly one of two concurrent appliers, and it runs inside the
     * applier's transaction so a rollback hands the event back untouched.
     */
    public function claim(): bool
    {
        return $this->newQuery()
            ->whereKey($this->getKey())
            ->whereNull('processed_at')
            ->update(['processed_at' => now()]) === 1;
    }

    public function isProcessed(): bool
    {
        return $this->processed_at !== null;
    }

    public function toWebhookResult(): WebhookResult
    {
        return WebhookResult::fromArray($this->payload ?? []);
    }

    /**
     * @return Builder<PaymentWebhookEvent>
     */
    public function prunable(): Builder
    {
        $days = (int) config('shopper.payment.reconciliation.prune_after_days', 90);

        return static::query()->where('created_at', '<', now()->subDays($days));
    }

    protected static function newFactory(): PaymentWebhookEventFactory
    {
        return PaymentWebhookEventFactory::new();
    }

    /**
     * @param  Builder<PaymentWebhookEvent>  $query
     * @return Builder<PaymentWebhookEvent>
     */
    #[Scope]
    protected function unprocessed(Builder $query): Builder
    {
        return $query->whereNull('processed_at');
    }

    /**
     * @param  Builder<PaymentWebhookEvent>  $query
     * @return Builder<PaymentWebhookEvent>
     */
    #[Scope]
    protected function forReference(Builder $query, string $reference): Builder
    {
        return $query->where('reference', $reference);
    }

    protected function casts(): array
    {
        return [
            'type' => WebhookAction::class,
            'payload' => 'array',
            'processed_at' => 'datetime',
        ];
    }
}
