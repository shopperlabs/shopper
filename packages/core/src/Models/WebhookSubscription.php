<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Shopper\Core\Database\Factories\WebhookSubscriptionFactory;
use Shopper\Core\Enum\WebhookDeliveryStatus;

/**
 * @property-read int $id
 * @property-read string $url
 * @property-read array<int, string> $events
 * @property-read string $secret
 * @property-read bool $is_active
 * @property-read ?string $description
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read Collection<int, WebhookDelivery> $deliveries
 */
class WebhookSubscription extends Model
{
    /** @use HasFactory<WebhookSubscriptionFactory> */
    use HasFactory;

    public const string ACTIVE_CACHE_KEY = 'shopper.webhooks.active_subscriptions';

    protected $guarded = [];

    /**
     * The active subscriptions, cached so the dispatch listener adds zero
     * queries to the domain-event hot path (checkout, payment). Invalidated
     * by every Eloquent write on this model and by `disableWhenFailing()`,
     * with a one-hour TTL as a safety net for raw query-builder writes.
     *
     * @return Collection<int, static>
     */
    public static function active(): Collection
    {
        return Cache::remember(
            self::ACTIVE_CACHE_KEY,
            3600,
            fn (): Collection => static::query()->where('is_active', true)->get(),
        );
    }

    public function getConnectionName(): ?string
    {
        return config('shopper.webhooks.database.connection') ?? parent::getConnectionName();
    }

    public function getTable(): string
    {
        return shopper_table('webhook_subscriptions');
    }

    public function listensTo(string $eventName): bool
    {
        return in_array($eventName, $this->events, true);
    }

    /**
     * Deactivate the subscription when its last N terminal deliveries
     * (N = `shopper.webhooks.disable_after_failures`) are all `failed`.
     *
     * The decision reads committed `webhook_deliveries` rows instead of
     * maintaining a counter column: a read-modify-write counter loses
     * increments under concurrent failing jobs. `rejected` (4xx) rows are
     * excluded — a deterministic payload rejection is not an endpoint
     * outage. The final update is guarded by `where is_active = true` so
     * concurrent callers flip the flag exactly once.
     */
    public function disableWhenFailing(): void
    {
        $threshold = (int) config('shopper.webhooks.disable_after_failures', 15);

        $recent = $this->deliveries()
            ->whereIn('status', [WebhookDeliveryStatus::Succeeded, WebhookDeliveryStatus::Failed])
            ->latest('id')
            ->limit($threshold)
            ->pluck('status');

        if ($recent->count() < $threshold || $recent->contains(fn (WebhookDeliveryStatus $status): bool => $status === WebhookDeliveryStatus::Succeeded)) {
            return;
        }

        static::query()
            ->whereKey($this->id)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        Cache::forget(self::ACTIVE_CACHE_KEY);
    }

    /**
     * @return HasMany<WebhookDelivery, $this>
     */
    public function deliveries(): HasMany
    {
        return $this->hasMany(WebhookDelivery::class);
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::ACTIVE_CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::ACTIVE_CACHE_KEY));
    }

    protected static function newFactory(): WebhookSubscriptionFactory
    {
        return WebhookSubscriptionFactory::new();
    }

    protected function casts(): array
    {
        return [
            'events' => 'array',
            'is_active' => 'boolean',
            'secret' => 'encrypted',
        ];
    }
}
