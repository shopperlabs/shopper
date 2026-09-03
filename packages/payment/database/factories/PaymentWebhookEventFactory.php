<?php

declare(strict_types=1);

namespace Shopper\Payment\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Shopper\Payment\DataTransferObjects\WebhookResult;
use Shopper\Payment\Enum\WebhookAction;
use Shopper\Payment\Models\PaymentWebhookEvent;

/**
 * @extends Factory<PaymentWebhookEvent>
 */
class PaymentWebhookEventFactory extends Factory
{
    protected $model = PaymentWebhookEvent::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $eventId = 'evt_'.$this->faker->unique()->uuid();
        $reference = 'pi_'.$this->faker->unique()->uuid();

        return [
            'driver' => 'manual',
            'event_id' => $eventId,
            'type' => WebhookAction::Captured,
            'reference' => $reference,
            'payload' => (new WebhookResult(action: WebhookAction::Captured, reference: $reference, eventId: $eventId))->toArray(),
            'processed_at' => null,
        ];
    }

    public function fromResult(WebhookResult $result): static
    {
        return $this->state(function (array $attributes) use ($result): array {
            $eventId = $result->eventId ?? $attributes['event_id'];

            return [
                'event_id' => $eventId,
                'type' => $result->action,
                'reference' => $result->reference,
                'payload' => [...$result->toArray(), 'event_id' => $eventId],
            ];
        });
    }

    public function processed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'processed_at' => now(),
        ]);
    }
}
