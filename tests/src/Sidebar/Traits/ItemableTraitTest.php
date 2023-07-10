<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Shopper\Sidebar\Domain\DefaultItem;
use Shopper\Tests\Sidebar\Stubs\ItemableStub;

beforeEach(function (): void {
    $this->container = Mockery::mock(Container::class);
    $this->itemable = new ItemableStub($this->container);
});

it('can add an item instance', function (): void {
    $item = new DefaultItem($this->container);
    $item->setName('demo');
    $this->itemable->addItem($item);

    expect($this->itemable->getItems())->toBeInstanceOf(Collection::class)
        ->and($this->itemable->getItems()->count())->toBe(1)
        ->and($this->itemable->getItems()->first()->getName())->toEqual('demo');
})->group('Traits');

it('can check if has items', function (): void {
    expect($this->itemable->hasItems())->toBeFalse();

    $item = new DefaultItem($this->container);
    $item->setName('demo');
    $this->itemable->addItem($item);

    expect($this->itemable->hasItems())->toBeTrue();
})->group('Traits');

it('can get sort items by weight', function (): void {
    $item = new DefaultItem($this->container);
    $item->setName('second');
    $item->weight(2);
    $this->itemable->addItem($item);

    $secondItem = new DefaultItem($this->container);
    $secondItem->setName('first');
    $secondItem->weight(1);
    $this->itemable->addItem($secondItem);

    expect($this->itemable->getItems()->count())->toBe(2)
        ->and($this->itemable->getItems()->first()->getName())->toEqual('first');
})->group('Traits');

it('can use blade heroicon as default type', function (): void {
    $item = new DefaultItem($this->container);
    $item->setName('demo');
    $item->setIcon('heroicon-o-chevron-right');

    expect($item->iconSvg())->toBeFalse();
})->group('Traits');
