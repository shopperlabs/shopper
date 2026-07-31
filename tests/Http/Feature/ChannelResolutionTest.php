<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Shopper\Core\Models\Channel;
use Shopper\Http\Contracts\ChannelResolver;

uses(Tests\Http\TestCase::class);

function resolvableChannel(string $slug, bool $enabled = true): Channel
{
    return Channel::factory()->create([
        'slug' => $slug,
        'is_enabled' => $enabled,
        'is_default' => false,
    ]);
}

it('resolves an enabled channel by its slug from the request header', function (): void {
    $channel = resolvableChannel('webstore');

    $request = Request::create('/store/anything');
    $request->headers->set('X-Shopper-Channel', 'webstore');

    expect(app(ChannelResolver::class)->resolve($request)?->getKey())->toBe($channel->getKey());
});

it('falls back to the configured default slug when no header is present', function (): void {
    config()->set('shopper.http.channel.default_slug', 'mobile-app');
    resolvableChannel('mobile-app');

    $resolved = app(ChannelResolver::class)->resolve(Request::create('/store/anything'));

    expect($resolved?->slug)->toBe('mobile-app');
});

it('returns null when no channel resolves and no default is configured', function (): void {
    $request = Request::create('/store/anything');
    $request->headers->set('X-Shopper-Channel', 'unknown-channel');

    expect(app(ChannelResolver::class)->resolve($request))->toBeNull();
});

it('ignores disabled channels', function (): void {
    resolvableChannel('webstore', enabled: false);

    $request = Request::create('/store/anything');
    $request->headers->set('X-Shopper-Channel', 'webstore');

    expect(app(ChannelResolver::class)->resolve($request))->toBeNull();
});
