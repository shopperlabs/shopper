<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Models\Traits\HasPublicId;

/**
 * @property-read int $id
 * @property-read ?string $public_id
 * @property-read string $name
 * @property-read ?string $resource_type
 * @property-read ?string $resource_id
 * @property-read array<string, mixed> $payload
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, WebhookDelivery> $deliveries
 */
class WebhookEvent extends Model
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
        return shopper_table('webhook_events');
    }

    /**
     * Events carry the stored JSON payloads — the heaviest webhook data.
     * A row is prunable once every delivery pointing at it has itself been
     * pruned and the failure-retention window has passed.
     *
     * @return Builder<static>
     */
    public function prunable(): Builder
    {
        $failedAfter = (int) config('shopper.webhooks.prune.failed_after_days', 90);

        return static::query()
            ->whereDoesntHave('deliveries')
            ->where('created_at', '<', now()->subDays($failedAfter));
    }

    /**
     * @return HasMany<WebhookDelivery, $this>
     */
    public function deliveries(): HasMany
    {
        return $this->hasMany(WebhookDelivery::class);
    }

    protected function casts(): array
    {
        return [
            'payload' => 'encrypted:array',
        ];
    }
}
