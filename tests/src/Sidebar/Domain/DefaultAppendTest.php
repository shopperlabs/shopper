<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use Shopper\Sidebar\Contracts\Builder\Append;
use Shopper\Sidebar\Domain\DefaultAppend;
use Shopper\Tests\Sidebar\Stubs\AppendStub;

beforeEach(function (): void {
    $this->container = Mockery::mock(Container::class);
    $this->append = new DefaultAppend($this->container);
});

it('can instantiate new append', function (): void {
    $append = new DefaultAppend($this->container);

    expect($append)->toBeInstanceOf(DefaultAppend::class);
})->group('Domain');

it('can have custom appends', function (): void {
    $append = new AppendStub($this->container);

    expect($append)->toBeInstanceOf(Append::class);
})->group('Domain');

it('can set name', function (): void {
    $this->append->setName('test');

    expect($this->append->getName())->toBe('test');
})->group('Domain');

it('can set icon', function (): void {
    $this->append->setIcon('test');

    expect($this->append->getIcon())->toBe('test');
})->group('Domain');

it('can set url', function (): void {
    $this->append->setUrl('test');

    expect($this->append->getUrl())->toBe('test');
})->group('Domain');

it('append can be cached', function (): void {
    $this->append->setName('name');
    $this->append->setUrl('url');
    $this->append->setIcon('icon');

    $serialized = serialize($this->append);
    $unserialized = unserialize($serialized);

    expect($unserialized)->toBeInstanceOf(Append::class)
        ->and($unserialized->getName())->toBe('name')
        ->and($unserialized->getUrl())->toBe('url')
        ->and($unserialized->getIcon())->toBe('icon');
})->group('Domain');
