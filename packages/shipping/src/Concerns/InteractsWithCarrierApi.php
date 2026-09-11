<?php

declare(strict_types=1);

namespace Shopper\Shipping\Concerns;

use Closure;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Shipping\DataTransferObjects\TrackingEvent;
use Shopper\Shipping\Exceptions\ShippingException;

trait InteractsWithCarrierApi
{
    /**
     * @param  Closure(): array{0: string, 1: int}  $request
     */
    protected function cachedToken(string $key, Closure $request): string
    {
        $key = 'shopper.shipping.token.'.$key;
        $token = $this->storedToken($key);

        if ($token !== null) {
            return $token;
        }

        $lock = Cache::lock($key.'.lock', 10);
        $lock->block(5);

        try {
            return $this->storedToken($key) ?? $this->storeToken($key, $request);
        } finally {
            $lock->release();
        }
    }

    /**
     * @return array{0: string, 1: int}
     */
    protected function readToken(Response $response): array
    {
        if ($response->failed()) {
            throw ShippingException::apiError($this->code(), $this->carrierMessage($response));
        }

        $token = $response->json('access_token');

        if (! is_string($token) || $token === '') {
            throw ShippingException::invalidResponse($this->code());
        }

        return [$token, (int) $response->json('expires_in', 3600)];
    }

    /**
     * @param  Collection<int, TrackingEvent>  $events
     */
    protected function highestStatus(Collection $events): ShipmentStatus
    {
        return $events
            ->sortBy(fn (TrackingEvent $event): int => $event->occurredAt->getTimestamp())
            ->reduce(
                fn (ShipmentStatus $carry, TrackingEvent $event): ShipmentStatus => $event->status->rank() >= $carry->rank()
                    ? $event->status
                    : $carry,
                ShipmentStatus::Pending,
            );
    }

    protected function carrierMessage(Response $response): string
    {
        $paths = [
            'response.errors.0.message',
            'errors.0.message',
            'output.alerts.0.message',
            'error_description',
            'error.message',
        ];

        foreach ($paths as $path) {
            $message = $response->json($path);

            if (is_string($message) && $message !== '') {
                return $message;
            }
        }

        return 'HTTP '.$response->status();
    }

    protected function carrierText(mixed $source, string $path): ?string
    {
        $value = data_get($source, $path);

        return is_string($value) && $value !== '' ? $value : null;
    }

    private function storedToken(string $key): ?string
    {
        $token = Cache::get($key);

        return is_string($token) && $token !== '' ? $token : null;
    }

    /**
     * @param  Closure(): array{0: string, 1: int}  $request
     */
    private function storeToken(string $key, Closure $request): string
    {
        [$token, $expiresIn] = $request();

        Cache::put($key, $token, max(60, $expiresIn - 60));

        return $token;
    }
}
