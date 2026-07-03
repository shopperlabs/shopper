<?php

declare(strict_types=1);

namespace Shopper\Core\Webhooks;

use Closure;
use RuntimeException;

final class WebhookUrl
{
    /** @var (Closure(string): list<string>)|null */
    private static ?Closure $resolveHostUsing = null;

    /**
     * Replace the DNS resolver used by the guard. Pass null to restore the
     * real resolver. Refused outside the test environment: this is the only
     * lever that can neuter the SSRF guard, so it must never be reachable
     * from production code or a compromised dependency.
     *
     * @param  (Closure(string): list<string>)|null  $resolver
     */
    public static function resolveHostUsing(?Closure $resolver): void
    {
        if (! app()->runningUnitTests()) {
            throw new RuntimeException('The webhook DNS resolver can only be overridden in the test environment.');
        }

        self::$resolveHostUsing = $resolver;
    }

    /**
     * SSRF guard for merchant-supplied delivery URLs.
     *
     * Rejects the URL unless: scheme is https, the host resolves, and every
     * resolved address — both A and AAAA records, since curl may connect
     * over either family — passes `FILTER_FLAG_NO_PRIV_RANGE` and
     * `FILTER_FLAG_NO_RES_RANGE` (blocks RFC 1918, loopback, link-local —
     * including cloud metadata endpoints such as 169.254.169.254).
     *
     * Must run at subscription time and again at delivery time: DNS may be
     * re-pointed to a private address between the two (DNS rebinding). At
     * delivery time, pair with `safeAddressFor()` + `pinnedResolveOptions()`
     * so the connection uses the exact address that was validated.
     */
    public static function isSafe(string $url): bool
    {
        return self::safeAddressFor($url) !== null;
    }

    /**
     * Validate the URL and return one vetted address to connect to, or null
     * when the URL is unsafe. All resolved addresses must pass the guard —
     * a host mixing one public and one private address is rejected outright.
     */
    public static function safeAddressFor(string $url): ?string
    {
        $parts = parse_url($url);

        if (! is_array($parts) || ($parts['scheme'] ?? null) !== 'https' || ! isset($parts['host'])) {
            return null;
        }

        $host = $parts['host'];

        if (! self::hostIsAllowed($host)) {
            return null;
        }

        $addresses = filter_var($host, FILTER_VALIDATE_IP) !== false
            ? [$host]
            : self::resolve($host);

        if ($addresses === []) {
            return null;
        }

        foreach ($addresses as $address) {
            if (filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                return null;
            }
        }

        return $addresses[0];
    }

    /**
     * Curl options pinning the connection to the vetted address, so the
     * HTTP client cannot re-resolve DNS to a different (possibly private)
     * address between validation and connect. TLS SNI and the Host header
     * keep using the original hostname.
     *
     * @return array<int, mixed>
     */
    public static function pinnedResolveOptions(string $url, string $address): array
    {
        $parts = parse_url($url);
        $host = is_array($parts) ? ($parts['host'] ?? null) : null;

        if ($host === null || filter_var($host, FILTER_VALIDATE_IP) !== false) {
            return [];
        }

        $port = is_array($parts) ? ($parts['port'] ?? 443) : 443;

        return [
            CURLOPT_RESOLVE => ["{$host}:{$port}:{$address}"],
        ];
    }

    /**
     * A host passes when the allowlist is empty (open by default) or when it
     * matches an allowlisted host exactly or as a subdomain. Locks deliveries
     * to known integrations so a compromised admin cannot redirect the store's
     * PII stream to an arbitrary endpoint.
     */
    private static function hostIsAllowed(string $host): bool
    {
        /** @var list<string> $allowed */
        $allowed = (array) config('shopper.webhooks.allowed_hosts', []);

        if ($allowed === []) {
            return true;
        }

        $host = mb_strtolower($host);

        foreach ($allowed as $entry) {
            $entry = mb_strtolower(mb_trim($entry));

            if ($host === $entry || str_ends_with($host, '.'.$entry)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return list<string>
     */
    private static function resolve(string $host): array
    {
        if (self::$resolveHostUsing !== null) {
            return (self::$resolveHostUsing)($host);
        }

        $records = @dns_get_record($host, DNS_A | DNS_AAAA) ?: [];

        return array_values(array_filter(array_map(
            fn (array $record): ?string => $record['ip'] ?? $record['ipv6'] ?? null,
            $records,
        )));
    }
}
