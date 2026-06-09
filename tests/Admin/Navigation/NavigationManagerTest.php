<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Shopper\Contracts\NavigationGroup;
use Shopper\Contracts\NavigationItem;
use Shopper\Navigation\AbstractNavigationItem;
use Shopper\Navigation\NavigationManager;

uses(Tests\Admin\TestCase::class);

enum FakeNavGroup: string implements NavigationGroup
{
    case Primary = 'primary';
    case Secondary = 'secondary';

    public function getLabel(): string
    {
        return match ($this) {
            self::Primary => 'Primary',
            self::Secondary => 'Secondary',
        };
    }

    public function order(): int
    {
        return match ($this) {
            self::Primary => 10,
            self::Secondary => 20,
        };
    }
}

final class FakeNavManager extends NavigationManager
{
    protected function resolveGroup(string $value): ?NavigationGroup
    {
        return FakeNavGroup::tryFrom($value);
    }
}

abstract class FakeNavItem extends AbstractNavigationItem
{
    public function icon(): string
    {
        return 'untitledui-star';
    }
}

final class PrimaryAlpha extends FakeNavItem
{
    public function name(): string
    {
        return 'Primary Alpha';
    }

    public function order(): int
    {
        return 1;
    }

    public function group(): string
    {
        return FakeNavGroup::Primary->value;
    }
}

final class PrimaryBeta extends FakeNavItem
{
    public function name(): string
    {
        return 'Primary Beta';
    }

    public function order(): int
    {
        return 5;
    }

    public function group(): string
    {
        return FakeNavGroup::Primary->value;
    }
}

final class SecondaryItem extends FakeNavItem
{
    public function name(): string
    {
        return 'Secondary Item';
    }

    public function order(): int
    {
        return 2;
    }

    public function group(): string
    {
        return FakeNavGroup::Secondary->value;
    }
}

final class CustomGroupItem extends FakeNavItem
{
    public function name(): string
    {
        return 'Custom Group Item';
    }

    public function order(): int
    {
        return 50;
    }

    public function group(): string
    {
        return 'integrations';
    }
}

final class UngroupedItem extends FakeNavItem
{
    public function name(): string
    {
        return 'Ungrouped Item';
    }

    public function order(): int
    {
        return 99;
    }
}

final class HiddenItem extends FakeNavItem
{
    public function name(): string
    {
        return 'Hidden Item';
    }

    public function group(): string
    {
        return FakeNavGroup::Primary->value;
    }
}

function makeNavManager(array $classes): FakeNavManager
{
    $manager = new FakeNavManager;

    foreach ($classes as $class => $enabled) {
        if (is_int($class)) {
            $manager->add($enabled);

            continue;
        }

        $manager->add($class, $enabled);
    }

    return $manager;
}

it('registers, enables and disables items fluently', function (): void {
    $manager = (new FakeNavManager)
        ->register([PrimaryAlpha::class => true])
        ->add(PrimaryBeta::class)
        ->disable(PrimaryAlpha::class);

    expect($manager->registered())->toBe([
        PrimaryAlpha::class => false,
        PrimaryBeta::class => true,
    ]);
})->group('Navigation');

it('returns enabled items sorted by order()', function (): void {
    $manager = makeNavManager([PrimaryBeta::class, PrimaryAlpha::class, SecondaryItem::class]);

    expect($manager->all()->map(fn (NavigationItem $item): string => $item->name())->all())
        ->toBe(['Primary Alpha', 'Secondary Item', 'Primary Beta']);
})->group('Navigation');

it('skips disabled items', function (): void {
    $manager = makeNavManager([
        PrimaryAlpha::class => true,
        PrimaryBeta::class => false,
    ]);

    expect($manager->all())->toHaveCount(1)
        ->and($manager->all()->first()->name())->toBe('Primary Alpha');
})->group('Navigation');

it('applies the optional filter callback to all()', function (): void {
    $manager = makeNavManager([PrimaryAlpha::class, HiddenItem::class]);

    $visible = $manager->all(fn (NavigationItem $item): bool => $item->name() !== 'Hidden Item');

    expect($visible)->toHaveCount(1)
        ->and($visible->first()->name())->toBe('Primary Alpha');
})->group('Navigation');

it('orders known groups by NavigationGroup::order() then items by item order()', function (): void {
    $manager = makeNavManager([
        SecondaryItem::class,
        PrimaryBeta::class,
        PrimaryAlpha::class,
    ]);

    $grouped = $manager->grouped();

    expect($grouped)->toBeInstanceOf(Collection::class)
        ->and($grouped->pluck('group')->all())->toBe(['primary', 'secondary'])
        ->and($grouped[0]['label'])->toBe('Primary')
        ->and($grouped[0]['items']->map(fn (NavigationItem $item): string => $item->name())->all())
        ->toBe(['Primary Alpha', 'Primary Beta']);
})->group('Navigation');

it('renders custom string groups between known groups and the ungrouped bucket', function (): void {
    $manager = makeNavManager([
        UngroupedItem::class,
        CustomGroupItem::class,
        PrimaryAlpha::class,
    ]);

    $grouped = $manager->grouped();

    expect($grouped)->toHaveCount(3)
        ->and($grouped[0]['group'])->toBe('primary')
        ->and($grouped[1]['group'])->toBe('integrations')
        ->and($grouped[1]['label'])->toBe('Integrations')
        ->and($grouped[2]['group'])->toBeNull()
        ->and($grouped[2]['label'])->toBeNull();
})->group('Navigation');

it('omits empty group buckets and filters grouped() items', function (): void {
    $manager = makeNavManager([PrimaryAlpha::class, SecondaryItem::class]);

    $grouped = $manager->grouped(fn (NavigationItem $item): bool => $item->group() === 'primary');

    expect($grouped)->toHaveCount(1)
        ->and($grouped[0]['group'])->toBe('primary');
})->group('Navigation');

it('returns an empty collection when no items are registered', function (): void {
    expect((new FakeNavManager)->grouped())->toHaveCount(0);
})->group('Navigation');
