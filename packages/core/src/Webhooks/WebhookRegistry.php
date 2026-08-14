<?php

declare(strict_types=1);

namespace Shopper\Core\Webhooks;

use Closure;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;
use LogicException;
use Shopper\Core\Contracts\WebhookPayloadSerializer;
use Shopper\Core\Listeners\DispatchWebhooksListener;

final class WebhookRegistry
{
    /** @var array<class-string, string> */
    private array $events = [];

    /** @var array<class-string, true> */
    private array $defaults = [];

    /** @var array<class-string, Closure|class-string<WebhookPayloadSerializer>> */
    private array $serializers = [];

    /** @var array<string, true> */
    private array $warned = [];

    private bool $seeded = false;

    private bool $activated = false;

    public function __construct(
        private readonly DispatcherContract $dispatcher,
    ) {}

    /**
     * Config entries are defaults: code may override their public name and
     * attach a serializer to them. Only a conflict between two code
     * registrations is a programming error and throws.
     *
     * @param  class-string  $eventClass
     * @param  (Closure(object): array{resource_type: ?string, resource_id: ?string, data: array<string, mixed>})|class-string<WebhookPayloadSerializer>|null  $serializer
     */
    public function register(string $eventClass, string $name, Closure|string|null $serializer = null): self
    {
        $this->ensureSeeded();

        $existing = $this->events[$eventClass] ?? null;

        if ($existing !== null && ! isset($this->defaults[$eventClass])) {
            if ($existing !== $name) {
                throw new LogicException("Webhook event [{$eventClass}] is already registered as [{$existing}].");
            }

            if ($serializer !== null) {
                throw new LogicException("Webhook event [{$eventClass}] is already registered and its serializer cannot be replaced.");
            }
        }

        $owner = array_search($name, $this->events, true);

        if ($owner !== false && $owner !== $eventClass) {
            $this->warnOnce("name:{$name}", 'Webhook event ignored: the public name is already used by another event.', [
                'event' => $eventClass,
                'name' => $name,
                'owner' => $owner,
            ]);

            return $this;
        }

        if ($serializer !== null) {
            $this->serializers[$eventClass] = $serializer;
        }

        if ($existing === null && $this->activated) {
            $this->listen($eventClass);
        }

        unset($this->defaults[$eventClass]);

        $this->events[$eventClass] = $name;

        return $this;
    }

    public function activate(): void
    {
        if ($this->activated) {
            return;
        }

        $this->seeded = true;
        $this->seedConfiguredEvents();

        foreach (array_keys($this->events) as $eventClass) {
            $this->listen($eventClass);
        }

        $this->activated = true;
    }

    /**
     * @return array<class-string, string>
     */
    public function events(): array
    {
        $this->ensureSeeded();

        return $this->events;
    }

    public function nameFor(string $eventClass): ?string
    {
        $this->ensureSeeded();

        return $this->events[$eventClass] ?? null;
    }

    /**
     * Null means no serializer is registered for the event, never a broken
     * one: a registered serializer returning a malformed shape is normalised
     * here so it can never be mistaken for an absent serializer and silently
     * fall back to the default payload.
     *
     * @return ?array{resource_type: ?string, resource_id: ?string, data: array<string, mixed>}
     */
    public function serialize(object $event): ?array
    {
        $serializer = $this->serializers[$event::class] ?? null;

        if ($serializer === null) {
            return null;
        }

        $payload = $serializer instanceof Closure
            ? $serializer($event)
            : resolve($serializer)->serialize($event);

        return [
            'resource_type' => $payload['resource_type'] ?? null,
            'resource_id' => $payload['resource_id'] ?? null,
            'data' => $payload['data'] ?? [],
        ];
    }

    private function ensureSeeded(): void
    {
        if ($this->seeded) {
            return;
        }

        $this->seeded = true;
        $this->seedConfiguredEvents();
    }

    private function seedConfiguredEvents(): void
    {
        foreach ((array) config('shopper.webhooks.events', []) as $eventClass => $name) {
            if (! is_string($eventClass) || ! is_string($name)) {
                $this->warnOnce(
                    'malformed:'.(is_string($eventClass) ? $eventClass : (string) json_encode($eventClass)),
                    'Webhook config entry ignored: the event class and public name must both be strings.',
                    ['event' => $eventClass],
                );

                continue;
            }

            if (isset($this->events[$eventClass])) {
                continue;
            }

            if (in_array($name, $this->events, true)) {
                $this->warnOnce("name:{$name}", 'Webhook config entry ignored: the public name is already used by another event.', [
                    'event' => $eventClass,
                    'name' => $name,
                ]);

                continue;
            }

            $this->defaults[$eventClass] = true;
            $this->events[$eventClass] = $name;
        }
    }

    private function listen(string $eventClass): void
    {
        if ($this->dispatcher instanceof Dispatcher
            && in_array(DispatchWebhooksListener::class, $this->dispatcher->getRawListeners()[$eventClass] ?? [], true)) {
            return;
        }

        $this->dispatcher->listen($eventClass, DispatchWebhooksListener::class);
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function warnOnce(string $key, string $message, array $context): void
    {
        if (isset($this->warned[$key])) {
            return;
        }

        $this->warned[$key] = true;

        Log::warning($message, $context);
    }
}
