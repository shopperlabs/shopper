<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use Illuminate\Contracts\Routing\UrlGenerator;
use Shopper\Tests\Sidebar\Stubs\RouteableStub;

beforeEach(function (): void {
    $this->container = Mockery::mock(Container::class);
    $this->routeable = new RouteableStub($this->container);
});

it('can set url', function (): void {
    $this->routeable->setUrl('url');

    expect($this->routeable->getUrl())->toEqual('url');
})->group('Traits');

it('can set route', function (): void {
    $url = Mockery::mock(UrlGenerator::class);
    $url->shouldReceive('route')->andReturn('url');

    $this->container->shouldReceive('make')->andReturn($url);
    $this->routeable->route('route');

    expect($this->routeable->getUrl())->toEqual('url');
})->group('Traits');
