<?php

declare(strict_types=1);

use Shopper\Core\Models\Channel;

uses(Tests\Core\TestCase::class);

describe('ChannelObserver', function (): void {
    it('keeps a default channel enabled on create', function (): void {
        $channel = Channel::factory()->create([
            'is_default' => true,
            'is_enabled' => false,
        ]);

        expect($channel->is_enabled)->toBeTrue();
    });

    it('re-enables a default channel when it is disabled', function (): void {
        $channel = Channel::factory()->create([
            'is_default' => true,
            'is_enabled' => true,
        ]);

        $channel->update(['is_enabled' => false]);

        expect($channel->refresh()->is_enabled)->toBeTrue();
    });

    it('allows a non-default channel to be disabled', function (): void {
        $channel = Channel::factory()->create([
            'is_default' => false,
            'is_enabled' => true,
        ]);

        $channel->update(['is_enabled' => false]);

        expect($channel->refresh()->is_enabled)->toBeFalse();
    });

    it('unsets the previous default and force-enables the promoted channel', function (): void {
        $previous = Channel::query()->where('is_default', true)->first();

        $channel = Channel::factory()->create(['is_default' => false, 'is_enabled' => false]);
        $channel->update(['is_default' => true]);

        expect($previous->refresh()->is_default)->toBeFalse()
            ->and($channel->refresh())
            ->is_default->toBeTrue()
            ->is_enabled->toBeTrue();
    });
});
