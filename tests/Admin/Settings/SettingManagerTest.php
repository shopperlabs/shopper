<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Shopper\Contracts\SettingItem;
use Shopper\Enum\SettingGroup;
use Shopper\Settings\SettingManager;

uses(Tests\Admin\TestCase::class);

abstract class FakeGroupedSetting implements SettingItem
{
    public function description(): string
    {
        return '';
    }

    public function icon(): string
    {
        return 'untitledui-star';
    }

    public function url(): ?string
    {
        return '#';
    }

    public function permission(): ?string
    {
        return null;
    }
}

final class StoreGeneralFake extends FakeGroupedSetting
{
    public function name(): string
    {
        return 'Store General';
    }

    public function order(): int
    {
        return 1;
    }

    public function group(): ?string
    {
        return SettingGroup::Store->value;
    }
}

final class StoreLegalFake extends FakeGroupedSetting
{
    public function name(): string
    {
        return 'Store Legal';
    }

    public function order(): int
    {
        return 6;
    }

    public function group(): ?string
    {
        return SettingGroup::Store->value;
    }
}

final class SellingPaymentFake extends FakeGroupedSetting
{
    public function name(): string
    {
        return 'Selling Payment';
    }

    public function order(): int
    {
        return 4;
    }

    public function group(): ?string
    {
        return SettingGroup::Selling->value;
    }
}

final class TeamStaffFake extends FakeGroupedSetting
{
    public function name(): string
    {
        return 'Team Staff';
    }

    public function order(): int
    {
        return 2;
    }

    public function group(): ?string
    {
        return SettingGroup::Team->value;
    }
}

final class UngroupedFake extends FakeGroupedSetting
{
    public function name(): string
    {
        return 'Ungrouped Addon';
    }

    public function order(): int
    {
        return 99;
    }

    public function group(): ?string
    {
        return null;
    }
}

final class CustomGroupFake extends FakeGroupedSetting
{
    public function name(): string
    {
        return 'Third-party Addon';
    }

    public function order(): int
    {
        return 50;
    }

    public function group(): ?string
    {
        return 'integrations';
    }
}

function registerGroupedFakes(array $classes): SettingManager
{
    $manager = new SettingManager;
    foreach ($classes as $class) {
        $manager->add($class);
    }
    app()->instance(SettingManager::class, $manager);

    return $manager;
}

it('groups items by their string group key', function (): void {
    $manager = registerGroupedFakes([
        StoreGeneralFake::class,
        SellingPaymentFake::class,
        TeamStaffFake::class,
    ]);

    $grouped = $manager->grouped();

    expect($grouped)->toBeInstanceOf(Collection::class)
        ->and($grouped)->toHaveCount(3)
        ->and($grouped[0]['group'])->toBe(SettingGroup::Store->value)
        ->and($grouped[1]['group'])->toBe(SettingGroup::Selling->value)
        ->and($grouped[2]['group'])->toBe(SettingGroup::Team->value);
})->group('Settings');

it('resolves labels for known enum groups', function (): void {
    $manager = registerGroupedFakes([
        StoreGeneralFake::class,
    ]);

    $grouped = $manager->grouped();

    expect($grouped[0]['label'])->toBe(SettingGroup::Store->getLabel());
})->group('Settings');

it('orders enum-backed groups by SettingGroup::order()', function (): void {
    $manager = registerGroupedFakes([
        TeamStaffFake::class,
        StoreGeneralFake::class,
        SellingPaymentFake::class,
    ]);

    $grouped = $manager->grouped();

    expect($grouped->pluck('group')->all())->toBe([
        SettingGroup::Store->value,
        SettingGroup::Selling->value,
        SettingGroup::Team->value,
    ]);
})->group('Settings');

it('orders items within a group by item order()', function (): void {
    $manager = registerGroupedFakes([
        StoreLegalFake::class,
        StoreGeneralFake::class,
    ]);

    $grouped = $manager->grouped();
    $storeItems = $grouped[0]['items'];

    expect($storeItems->map(fn (SettingItem $item): string => $item->name())->all())->toBe([
        'Store General',
        'Store Legal',
    ]);
})->group('Settings');

it('places ungrouped items in a trailing null bucket', function (): void {
    $manager = registerGroupedFakes([
        UngroupedFake::class,
        StoreGeneralFake::class,
    ]);

    $grouped = $manager->grouped();

    expect($grouped)->toHaveCount(2)
        ->and($grouped[0]['group'])->toBe(SettingGroup::Store->value)
        ->and($grouped[1]['group'])->toBeNull()
        ->and($grouped[1]['label'])->toBeNull()
        ->and($grouped[1]['items']->first()->name())->toBe('Ungrouped Addon');
})->group('Settings');

it('renders custom string groups between enum groups and ungrouped bucket', function (): void {
    $manager = registerGroupedFakes([
        UngroupedFake::class,
        CustomGroupFake::class,
        StoreGeneralFake::class,
    ]);

    $grouped = $manager->grouped();

    expect($grouped)->toHaveCount(3)
        ->and($grouped[0]['group'])->toBe(SettingGroup::Store->value)
        ->and($grouped[1]['group'])->toBe('integrations')
        ->and($grouped[1]['label'])->toBe('Integrations')
        ->and($grouped[2]['group'])->toBeNull();
})->group('Settings');

it('omits empty group buckets', function (): void {
    $manager = registerGroupedFakes([
        StoreGeneralFake::class,
    ]);

    $grouped = $manager->grouped();

    expect($grouped)->toHaveCount(1)
        ->and($grouped[0]['group'])->toBe(SettingGroup::Store->value);
})->group('Settings');

it('returns an empty collection when no items are registered', function (): void {
    $manager = registerGroupedFakes([]);

    expect($manager->grouped())->toHaveCount(0);
})->group('Settings');

it('skips disabled items in grouped()', function (): void {
    $manager = new SettingManager;
    $manager->add(StoreGeneralFake::class, enabled: true);
    $manager->add(SellingPaymentFake::class, enabled: false);
    app()->instance(SettingManager::class, $manager);

    $grouped = $manager->grouped();

    expect($grouped)->toHaveCount(1)
        ->and($grouped[0]['group'])->toBe(SettingGroup::Store->value);
})->group('Settings');
