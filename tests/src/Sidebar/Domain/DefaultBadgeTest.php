<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use Shopper\Sidebar\Contracts\Builder\Badge;
use Shopper\Sidebar\Domain\DefaultBadge;
use Shopper\Tests\Sidebar\Stubs\BadgeStub;

beforeEach(function (): void {
    $this->container = Mockery::mock(Container::class);
    $this->badge = new DefaultBadge($this->container);
});

it('can be instantiate new badge', function (): void {
    $badge = new DefaultBadge($this->container);

    expect($badge)->toBeInstanceOf(DefaultBadge::class);
})->group('Domain');

it('can have custom badge', function (): void {
    $badge = new BadgeStub($this->container);

    expect($badge)->toBeInstanceOf(Badge::class);
})->group('Domain');

it('can set value', function (): void {
    $this->badge->setValue('value');

    expect($this->badge->getValue())->toBe('value');
})->group('Domain');

it('can set a class', function (): void {
    $this->badge->setClass('class');

    expect($this->badge->getClass())->toBe('class');
})->group('Domain');

it('badge can be cached', function (): void {
    $this->badge->setValue('value');
    $this->badge->setClass('class');

    $serialized = serialize($this->badge);
    $unserialized = unserialize($serialized);

    expect($unserialized)->toBeInstanceOf(Badge::class)
        ->and($unserialized->getValue())->toBe('value')
        ->and($unserialized->getClass())->toBe('class');
})->group('Domain');
