<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Domain\DefaultGroup;
use Shopper\Sidebar\Domain\DefaultItem;
use Shopper\Tests\Sidebar\Stubs\GroupStub;

beforeEach(function (): void {
    $this->container = Mockery::mock(Container::class);
    $this->group = new DefaultGroup($this->container);
});

it('can be instantiate new group', function (): void {
    $group = new DefaultGroup($this->container);

    expect($group)->toBeInstanceOf(DefaultGroup::class);
})->group('Domain');

it('can have custom group', function (): void {
    $group = new GroupStub($this->container);

    expect($group)->toBeInstanceOf(Group::class);
})->group('Domain');

it('group can be cached', function (): void {
    $item = new DefaultItem($this->container);
    $item->setName('test');
    $this->group->addItem($item);

    $serialized = serialize($this->group);
    $unserialized = unserialize($serialized);

    expect($unserialized)->toBeInstanceOf(Group::class)
        ->and($unserialized->getItems())->toBeInstanceOf(Collection::class)
        ->and($unserialized->getItems()->count())->toBe(1);
})->group('Domain');
