<?php

declare(strict_types=1);

use Shopper\Core\Channel\ChannelManager;
use Shopper\Core\Channel\Contracts\ChannelDriver;
use Shopper\Core\Channel\Facades\Channels;

uses(Tests\Core\TestCase::class);

function fakeChannelDriver(string $code, bool $configured = true): ChannelDriver
{
    return new class($code, $configured) implements ChannelDriver
    {
        public function __construct(private string $code, private bool $configured) {}

        public function code(): string
        {
            return $this->code;
        }

        public function name(): string
        {
            return ucfirst($this->code);
        }

        public function logo(): ?string
        {
            return null;
        }

        public function isConfigured(): bool
        {
            return $this->configured;
        }
    };
}

describe(ChannelManager::class, function (): void {
    it('resolves a registered driver', function (): void {
        $manager = resolve(ChannelManager::class)
            ->extend('custom', fn (): ChannelDriver => fakeChannelDriver('custom'));

        expect($manager->driver('custom'))
            ->toBeInstanceOf(ChannelDriver::class)
            ->and($manager->driver('custom')->name())->toBe('Custom');
    });

    it('throws for an unknown driver', function (): void {
        resolve(ChannelManager::class)->driver('missing');
    })->throws(InvalidArgumentException::class);

    it('lists available driver codes', function (): void {
        $manager = resolve(ChannelManager::class)
            ->extend('custom', fn (): ChannelDriver => fakeChannelDriver('custom'))
            ->extend('other', fn (): ChannelDriver => fakeChannelDriver('other'));

        expect($manager->availableDrivers())->toBe(['web', 'custom', 'other']);
    });

    it('resolves the built-in web driver by default', function (): void {
        expect(resolve(ChannelManager::class)->driver()->code())->toBe('web');
    });

    it('resolves the logo for a known driver and null for an unknown one', function (): void {
        $manager = resolve(ChannelManager::class);

        expect($manager->logoFor('web'))->toBe($manager->driver('web')->logo())
            ->and($manager->logoFor(null))->toBe($manager->driver('web')->logo())
            ->and($manager->logoFor('unknown'))->toBeNull();
    });

    it('keeps only configured drivers in the configured set', function (): void {
        $manager = resolve(ChannelManager::class)
            ->extend('custom', fn (): ChannelDriver => fakeChannelDriver('custom', configured: true))
            ->extend('other', fn (): ChannelDriver => fakeChannelDriver('other', configured: false));

        expect($manager->configuredDrivers()->keys()->all())->toBe(['web', 'custom'])
            ->and($manager->isConfigured('custom'))->toBeTrue()
            ->and($manager->isConfigured('other'))->toBeFalse();
    });

    it('reports an unknown driver as not configured', function (): void {
        expect(resolve(ChannelManager::class)->isConfigured('missing'))->toBeFalse();
    });

    it('exposes the manager through the Channels facade', function (): void {
        Channels::extend('custom', fn (): ChannelDriver => fakeChannelDriver('custom'));

        expect(Channels::driver('custom')->code())->toBe('custom')
            ->and(Channels::availableDrivers())->toBe(['web', 'custom']);
    });

    it('reports a driver whose configuration probe throws as not configured', function (): void {
        $manager = resolve(ChannelManager::class)
            ->extend('faulty', fn (): ChannelDriver => new class implements ChannelDriver
            {
                public function code(): string
                {
                    return 'faulty';
                }

                public function name(): string
                {
                    return 'Faulty';
                }

                public function logo(): ?string
                {
                    return null;
                }

                public function isConfigured(): bool
                {
                    throw new RuntimeException('boom');
                }
            });

        expect($manager->isConfigured('faulty'))->toBeFalse();
    });
});
