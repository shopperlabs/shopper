<?php

declare(strict_types=1);

use Filament\Support\Contracts\ScalableIcon;
use Filament\Support\Enums\IconSize;
use Shopper\Contracts\SettingItem;
use Shopper\Livewire\Concerns\WithSettingsBreadcrumbs;
use Shopper\Settings\SettingManager;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Sidebar\Breadcrumbs\BreadcrumbLink;

uses(Tests\Admin\TestCase::class);

enum FakeScalableIcon: string implements ScalableIcon
{
    case Sliders = 'sliders';

    public function getIconForSize(IconSize $size): string
    {
        return 'untitledui-'.$this->value;
    }
}

enum FakePlainBackedIcon: string
{
    case Plain = 'plain-icon';
}

final class StringIconSetting implements SettingItem
{
    public function name(): string
    {
        return 'Carriers';
    }

    public function description(): string
    {
        return '';
    }

    public function icon(): string
    {
        return 'phosphor-truck-trailer';
    }

    public function url(): ?string
    {
        return '/admin/settings/carriers';
    }

    public function permission(): ?string
    {
        return null;
    }

    public function order(): int
    {
        return 1;
    }

    public function group(): ?string
    {
        return null;
    }
}

final class ScalableIconSetting implements SettingItem
{
    public function name(): string
    {
        return 'General';
    }

    public function description(): string
    {
        return '';
    }

    public function icon(): BackedEnum
    {
        return FakeScalableIcon::Sliders;
    }

    public function url(): ?string
    {
        return '/admin/settings/general';
    }

    public function permission(): ?string
    {
        return null;
    }

    public function order(): int
    {
        return 0;
    }

    public function group(): ?string
    {
        return null;
    }
}

final class PlainEnumIconSetting implements SettingItem
{
    public function name(): string
    {
        return 'Plain';
    }

    public function description(): string
    {
        return '';
    }

    public function icon(): BackedEnum
    {
        return FakePlainBackedIcon::Plain;
    }

    public function url(): ?string
    {
        return '/admin/settings/plain';
    }

    public function permission(): ?string
    {
        return null;
    }

    public function order(): int
    {
        return 2;
    }

    public function group(): ?string
    {
        return null;
    }
}

final class UrlLessSetting implements SettingItem
{
    public function name(): string
    {
        return 'Soon';
    }

    public function description(): string
    {
        return '';
    }

    public function icon(): string
    {
        return 'phosphor-clock';
    }

    public function url(): ?string
    {
        return null;
    }

    public function permission(): ?string
    {
        return null;
    }

    public function order(): int
    {
        return 3;
    }

    public function group(): ?string
    {
        return null;
    }
}

class SettingsBreadcrumbsHost
{
    use WithSettingsBreadcrumbs;
}

class SettingsBreadcrumbsHostWithPageCrumbs
{
    use WithSettingsBreadcrumbs;

    public function settingsPageBreadcrumbs(): array
    {
        return [new Breadcrumb(text: 'Carriers')];
    }
}

function registerFakeSettings(array $classes): void
{
    $manager = new SettingManager;
    foreach ($classes as $class) {
        $manager->add($class);
    }
    app()->instance(SettingManager::class, $manager);
}

it('resolves a plain string icon unchanged', function (): void {
    registerFakeSettings([StringIconSetting::class]);

    $links = (new SettingsBreadcrumbsHost)->settingsDropdownLinks();

    expect($links)->toHaveCount(1)
        ->and($links[0])->toBeInstanceOf(BreadcrumbLink::class)
        ->and($links[0]->icon)->toBe('phosphor-truck-trailer');
})->group('Breadcrumbs');

it('resolves a ScalableIcon backed enum to its namespaced blade name', function (): void {
    registerFakeSettings([ScalableIconSetting::class]);

    $links = (new SettingsBreadcrumbsHost)->settingsDropdownLinks();

    expect($links[0]->icon)->toBe('untitledui-sliders');
})->group('Breadcrumbs');

it('falls back to raw value for non-scalable backed enum icons', function (): void {
    registerFakeSettings([PlainEnumIconSetting::class]);

    $links = (new SettingsBreadcrumbsHost)->settingsDropdownLinks();

    expect($links[0]->icon)->toBe('plain-icon');
})->group('Breadcrumbs');

it('uses `#` as fallback when a setting has no URL', function (): void {
    registerFakeSettings([UrlLessSetting::class]);

    $links = (new SettingsBreadcrumbsHost)->settingsDropdownLinks();

    expect($links[0]->url)->toBe('#');
})->group('Breadcrumbs');

it('combines the settings root crumb with page-specific crumbs in order', function (): void {
    registerFakeSettings([]);

    $crumbs = (new SettingsBreadcrumbsHostWithPageCrumbs)->getSettingsBreadcrumbs();

    expect($crumbs)->toHaveCount(2)
        ->and($crumbs[0]->text)->toBe(__('shopper::pages/settings/global.menu'))
        ->and($crumbs[0]->icon)->toBe('phosphor-faders')
        ->and($crumbs[1]->text)->toBe('Carriers');
})->group('Breadcrumbs');

it('exposes the settings root crumb with the dropdown populated from SettingManager', function (): void {
    registerFakeSettings([ScalableIconSetting::class, StringIconSetting::class]);

    $root = (new SettingsBreadcrumbsHost)->settingsRootCrumb();

    expect($root->links)->toHaveCount(2)
        ->and($root->links[0]->text)->toBe('General')
        ->and($root->links[1]->text)->toBe('Carriers');
})->group('Breadcrumbs');
