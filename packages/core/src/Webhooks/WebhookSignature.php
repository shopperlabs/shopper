<?php

declare(strict_types=1);

namespace Shopper\Core\Webhooks;

final class WebhookSignature
{
    /**
     * Compute the `X-Shopper-Signature` header value for a payload.
     *
     * Returns `t=<unix-timestamp>,v1=<hmac>` where the HMAC-SHA256 digest
     * covers `"{timestamp}.{payload}"`. Binding the timestamp into the
     * signed message prevents replay of a captured request outside the
     * tolerance window enforced by `verify()`.
     */
    public static function sign(string $payload, string $secret, ?int $timestamp = null): string
    {
        $timestamp ??= now()->getTimestamp();

        $signature = hash_hmac('sha256', "{$timestamp}.{$payload}", $secret);

        return "t={$timestamp},v1={$signature}";
    }

    /**
     * Validate a signature header against the raw request body.
     *
     * Fails on: malformed header, timestamp drift beyond the tolerance
     * (`shopper.webhooks.signature_tolerance` seconds, default 300), or
     * digest mismatch. Comparison uses `hash_equals()` to stay
     * constant-time.
     */
    public static function verify(string $header, string $payload, string $secret, ?int $tolerance = null): bool
    {
        $tolerance ??= (int) config('shopper.webhooks.signature_tolerance', 300);

        if (! preg_match('/^t=(\d+),v1=([a-f0-9]{64})$/', $header, $matches)) {
            return false;
        }

        [, $timestamp, $signature] = $matches;

        if (abs(now()->getTimestamp() - (int) $timestamp) > $tolerance) {
            return false;
        }

        $expected = hash_hmac('sha256', "{$timestamp}.{$payload}", $secret);

        return hash_equals($expected, $signature);
    }
}
