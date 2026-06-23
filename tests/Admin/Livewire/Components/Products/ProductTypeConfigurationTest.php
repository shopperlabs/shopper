<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Setting;
use Shopper\Livewire\Components\Products\ProductTypeConfiguration;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

describe(ProductTypeConfiguration::class, function (): void {
    it('blocks writing the default product type setting for users without `system.settings`', function (): void {
        $user = User::factory()->create();
        $user->givePermissionTo('products.browse');
        $this->actingAs($user);

        Livewire::test(ProductTypeConfiguration::class, ['defaultProductType' => ProductType::Standard->value])
            ->set('hasConfig', false);

        expect(Setting::query()->where('key', 'default_product_type')->exists())->toBeFalse();
    });

    it('writes the default product type setting for users with `system.settings`', function (): void {
        $user = User::factory()->create();
        $user->givePermissionTo(['products.browse', 'system.settings']);
        $this->actingAs($user);

        Livewire::test(ProductTypeConfiguration::class, ['defaultProductType' => ProductType::Standard->value])
            ->set('hasConfig', true);

        expect(Setting::query()->where('key', 'default_product_type')->value('value'))
            ->toBe(ProductType::Standard->value);
    });
})->group('livewire', 'components', 'products', 'security');
