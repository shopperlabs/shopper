<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Shopper\Sidebar\Contracts\Builder\Append;
use Shopper\Sidebar\Contracts\Builder\Badge;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Domain\DefaultAppend;
use Shopper\Sidebar\Domain\DefaultBadge;
use Shopper\Sidebar\Domain\DefaultItem;
use Shopper\Tests\Sidebar\Stubs\ItemStub;

beforeEach(function (): void {
    $this->container = Mockery::mock(Container::class);
    $this->item = new DefaultItem($this->container);
});

it('can be instantiate new item', function (): void {
    $item = new DefaultItem($this->container);

    expect($item)->toBeInstanceOf(DefaultItem::class);
})->group('Domain');

it('can have custom item', function (): void {
    $group = new ItemStub($this->container);

    expect($group)->toBeInstanceOf(Item::class);
})->group('Domain');

it('can set name', function (): void {
    $this->item->setName('test');

    expect($this->item->getName())->toBe('test');
})->group('Domain');

it('can set icon', function (): void {
    $this->item->setIcon('icon');

    expect($this->item->getIcon())->toBe('icon');
})->group('Domain');

it('can set url', function (): void {
    $this->item->setUrl('url');

    expect($this->item->getUrl())->toBe('url');
})->group('Domain');

it('can set weight', function (): void {
    $this->item->weight(1);

    expect($this->item->getWeight())->toBe(1);
})->group('Domain');

it('can be cached', function (): void {
    $item = new DefaultItem($this->container);
    $item->setName('test');
    $this->item->addItem($item);

    $this->item->setName('test');
    $this->item->setIcon('icon');
    $this->item->setUrl('url');
    $this->item->weight(1);

    $serialized = serialize($this->item);
    $unserialized = unserialize($serialized);

    expect($unserialized)->toBeInstanceOf(Item::class)
        ->and($unserialized->getName())->toBe('test')
        ->and($unserialized->getIcon())->toBe('icon')
        ->and($unserialized->getUrl())->toBe('url')
        ->and($unserialized->getWeight())->toBe(1)
        ->and($unserialized->getItems())->toBeInstanceOf(Collection::class)
        ->and($unserialized->getItems()->count())->toBe(1);
})->group('Domain');

it('can add a badge instance', function (): void {
    $badge = new DefaultBadge($this->container);
    $badge->setValue(1);
    $this->item->addBadge($badge);

    expect($this->item->getBadges())->toBeInstanceOf(Collection::class)
        ->and($this->item->getBadges()->count())->toBe(1)
        ->and($this->item->getBadges()->first()->getValue())->toBe(1);
})->group('Domain');

it('can add a badge', function (): void {
    $mock = Mockery::mock(Badge::class);
    $this->container->shouldReceive('make')->with(Badge::class)->andReturn($mock);
    $mock->shouldReceive('setValue');
    $mock->shouldReceive('setClass');
    $mock->shouldReceive('getClass')->andReturn('className');
    $mock->shouldReceive('getValue')->andReturn(1);

    $this->item->badge(1, 'className');

    expect($this->item->getBadges())->toBeInstanceOf(Collection::class)
        ->and($this->item->getBadges()->count())->toBe(1)
        ->and($this->item->getBadges()->first()->getValue())->toBe(1)
        ->and($this->item->getBadges()->first()->getClass())->toBe('className');
})->group('Domain');

it('can add a append instance', function (): void {
    $append = new DefaultAppend($this->container);
    $append->setUrl('url');
    $this->item->addAppend($append);

    expect($this->item->getAppends())->toBeInstanceOf(Collection::class)
        ->and($this->item->getAppends()->count())->toBe(1)
        ->and($this->item->getAppends()->first()->getUrl())->toBe('url');
})->group('Domain');

it('can add a append', function (): void {
    $mock = Mockery::mock(Append::class);
    $this->container->shouldReceive('make')->with(Append::class)->andReturn($mock);
    $mock->shouldReceive('route');
    $mock->shouldReceive('setIcon');
    $mock->shouldReceive('setName');
    $mock->shouldReceive('getUrl')->andReturn('url');
    $mock->shouldReceive('getIcon')->andReturn('setIcon');
    $mock->shouldReceive('getName')->andReturn('setName');

    $this->item->append('route', 'setIcon', 'setName');

    expect($this->item->getAppends())->toBeInstanceOf(Collection::class)
        ->and($this->item->getAppends()->count())->toBe(1)
        ->and($this->item->getAppends()->first()->getUrl())->toBe('url');
})->group('Domain');
