<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Models\Channel;
use Shopper\Core\Models\Contracts\Channel as ChannelContract;

uses(Tests\Core\TestCase::class);

describe(Channel::class, function (): void {
    it('scopes enabled channels', function (): void {
        resolve(ChannelContract::class)::query()->first();

        Channel::factory()->count(2)->create(['is_enabled' => true]);
        Channel::factory()->count(3)->create(['is_enabled' => false]);

        expect(resolve(ChannelContract::class)::query()->scopes('enabled')->count())
            ->toBeGreaterThanOrEqual(3);
    });

    it('scopes default channel', function (): void {
        $channel = resolve(ChannelContract::class)::query()->scopes('default')->first();

        expect($channel)->not->toBeNull()
            ->and($channel->is_default)->toBeTrue();
    });

    it('has products relationship', function (): void {
        $channel = resolve(ChannelContract::class)::query()->first();

        expect($channel->products())->toBeInstanceOf(MorphToMany::class);
    });
})->group('channel', 'models');
