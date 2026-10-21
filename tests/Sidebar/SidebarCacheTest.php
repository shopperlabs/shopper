<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Shopper\Sidebar\AdminSidebar;
use Shopper\Sidebar\Contracts\Builder\Badge;
use Shopper\Sidebar\Contracts\Sidebar;
use Shopper\Sidebar\Infrastructure\ContainerResolver;
use Shopper\Sidebar\Infrastructure\SidebarResolver;
use Shopper\Sidebar\Infrastructure\SidebarSerializer;
use Shopper\Sidebar\Presentation\SidebarRenderer;
use Shopper\Sidebar\SidebarBuilder;
use Shopper\Sidebar\SidebarManager;
use Tests\Sidebar\Stubs\SidebarStub;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    config(['cache.default' => 'array', 'cache.stores.array.serialize' => true, 'cache.serializable_classes' => false]);
    Cache::forgetDriver('array');

    $this->asAdmin();
});

it('renders the cached sidebar like a freshly built one on a cache that refuses to unserialize objects', function (array $cache, string $sidebar): void {
    config(['sidebar.cache.method' => $cache['method']]);

    $renderer = app(SidebarRenderer::class);
    $expected = $renderer->render(app(ContainerResolver::class)->resolve($sidebar))->render();

    foreach ([1, 2] as $request) {
        expect($renderer->render(app(SidebarResolver::class)->resolve($sidebar))->render())->toBe($expected);
    }
})->with([
    'static' => [['method' => 'static']],
    'user-based' => [['method' => 'user-based']],
])->with([AdminSidebar::class, SidebarStub::class]);

it('keeps a hidden menu hidden in the cached sidebar', function (): void {
    $serializer = app(SidebarSerializer::class);
    $sidebar = app(ContainerResolver::class)->resolve(SidebarStub::class);
    $sidebar->getMenu()->setAuthorized(false);

    expect($serializer->unserialize($serializer->serialize($sidebar))->getMenu()->isAuthorized())->toBeFalse();
});

it('leaves classes outside the sidebar incomplete when it reads a cached sidebar', function (): void {
    $serializer = app(SidebarSerializer::class);
    $badge = fn (Sidebar $sidebar): Badge => $sidebar->getMenu()->getGroups()->first()->getItems()->first()->getBadges()->first();
    $sidebar = app(ContainerResolver::class)->resolve(SidebarStub::class);
    $badge($sidebar)->setValue(new ArrayObject);

    expect($badge($serializer->unserialize($serializer->serialize($sidebar)))->getValue())
        ->toBeInstanceOf(__PHP_Incomplete_Class::class);
});

it('builds the sidebar again once its cache is flushed', function (array $cache, int $builds): void {
    Event::fake([SidebarBuilder::class]);
    config(['sidebar.cache' => [...config('sidebar.cache'), ...$cache]]);

    $manager = app(SidebarManager::class)->register(AdminSidebar::class);

    app(SidebarResolver::class)->resolve(AdminSidebar::class);
    app(SidebarResolver::class)->resolve(AdminSidebar::class);
    app()->call([$manager, 'flush']);
    app(SidebarResolver::class)->resolve(AdminSidebar::class);

    Event::assertDispatchedTimes(SidebarBuilder::class, $builds);
})->with([
    'without cache' => [['method' => null], 3],
    'static' => [['method' => 'static'], 2],
    'user-based' => [['method' => 'user-based'], 2],
    'static without expiry' => [['method' => 'static', 'duration' => null], 2],
    'user-based without expiry' => [['method' => 'user-based', 'duration' => null], 2],
]);

it('caches the sidebar for each locale', function (array $cache): void {
    config(['sidebar.cache.method' => $cache['method']]);

    $renderer = app(SidebarRenderer::class);
    $renderer->render(app(SidebarResolver::class)->resolve(AdminSidebar::class))->render();

    app()->setLocale('fr');

    expect($renderer->render(app(SidebarResolver::class)->resolve(AdminSidebar::class))->render())
        ->toBe($renderer->render(app(ContainerResolver::class)->resolve(AdminSidebar::class))->render());
})->with([
    'static' => [['method' => 'static']],
    'user-based' => [['method' => 'user-based']],
]);

it('keys the user-based cache by the user of the guard resolved at request time', function (): void {
    Event::fake([SidebarBuilder::class]);
    config(['sidebar.cache.method' => 'user-based', 'auth.guards.admin' => ['driver' => 'session', 'provider' => 'users']]);

    $resolver = app(SidebarResolver::class);

    foreach ([$this->makeAdminUser(), $this->makeAdminUser()] as $admin) {
        $this->actingAs($admin, 'admin');
        $resolver->resolve(AdminSidebar::class);
    }

    Event::assertDispatchedTimes(SidebarBuilder::class, 2);
});
