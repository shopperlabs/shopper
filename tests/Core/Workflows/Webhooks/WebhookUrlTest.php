<?php

declare(strict_types=1);

use Shopper\Core\Webhooks\WebhookUrl;

uses(Tests\Core\TestCase::class);

afterEach(function (): void {
    WebhookUrl::resolveHostUsing(null);
});

describe(WebhookUrl::class, function (): void {
    it('rejects non-https schemes', function (): void {
        expect(WebhookUrl::isSafe('http://example.com/hook'))->toBeFalse();
    });

    it('rejects a url without a host', function (): void {
        expect(WebhookUrl::isSafe('https:///hook'))->toBeFalse();
    });

    it('rejects a hostname resolving to no address', function (): void {
        WebhookUrl::resolveHostUsing(fn (): array => []);

        expect(WebhookUrl::isSafe('https://nowhere.example.com/hook'))->toBeFalse();
    });

    it('rejects a host when any resolved address is private, even alongside public ones', function (): void {
        WebhookUrl::resolveHostUsing(fn (): array => ['93.184.216.34', '10.0.0.5']);

        expect(WebhookUrl::isSafe('https://multi.example.com/hook'))->toBeFalse();
    });

    it('rejects a host whose ipv6 record is loopback while its ipv4 is public', function (): void {
        WebhookUrl::resolveHostUsing(fn (): array => ['93.184.216.34', '::1']);

        expect(WebhookUrl::isSafe('https://dualstack.example.com/hook'))->toBeFalse();
    });

    it('accepts a public ip literal without DNS resolution', function (): void {
        expect(WebhookUrl::isSafe('https://93.184.216.34/hook'))->toBeTrue();
    });

    it('rejects private, loopback and metadata ip literals', function (string $url): void {
        expect(WebhookUrl::isSafe($url))->toBeFalse();
    })->with([
        'https://127.0.0.1/hook',
        'https://10.0.0.1/hook',
        'https://192.168.1.1/hook',
        'https://169.254.169.254/latest/meta-data',
    ]);

    it('pins the vetted address for hostname urls and never for ip literals', function (): void {
        expect(WebhookUrl::pinnedResolveOptions('https://receiver.test/hooks', '93.184.216.34'))
            ->toBe([CURLOPT_RESOLVE => ['receiver.test:443:93.184.216.34']])
            ->and(WebhookUrl::pinnedResolveOptions('https://receiver.test:8443/hooks', '93.184.216.34'))
            ->toBe([CURLOPT_RESOLVE => ['receiver.test:8443:93.184.216.34']])
            ->and(WebhookUrl::pinnedResolveOptions('https://93.184.216.34/hooks', '93.184.216.34'))
            ->toBe([]);
    });

    it('rejects a public host that is not on a non-empty allowlist', function (): void {
        config()->set('shopper.webhooks.allowed_hosts', ['erp.acme.com']);
        WebhookUrl::resolveHostUsing(fn (): array => ['93.184.216.34']);

        expect(WebhookUrl::isSafe('https://evil.example.com/hook'))->toBeFalse();
    });

    it('accepts an allowlisted host and its subdomains', function (): void {
        config()->set('shopper.webhooks.allowed_hosts', ['acme.com']);
        WebhookUrl::resolveHostUsing(fn (): array => ['93.184.216.34']);

        expect(WebhookUrl::isSafe('https://acme.com/hook'))->toBeTrue()
            ->and(WebhookUrl::isSafe('https://hooks.acme.com/hook'))->toBeTrue()
            ->and(WebhookUrl::isSafe('https://notacme.com/hook'))->toBeFalse();
    });
})->group('core', 'webhooks');
