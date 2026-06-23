<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Setting;
use Shopper\Livewire\Components\Products\ProductTypeConfiguration;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

describe(ProductTypeConfiguration::class, function (): void {
    it('blocks writing the default product type setting for users without `access_setting`', function (): void {
        $user = User::factory()->create();
        $user->givePermissionTo('browse_products');
        $this->actingAs($user);

        Livewire::test(ProductTypeConfiguration::class, ['defaultProductType' => ProductType::Standard])
            ->set('hasConfig', false);

        expect(Setting::query()->where('key', 'default_product_type')->exists())->toBeFalse();
    });

    it('writes the default product type setting for users with `access_setting`', function (): void {
        $user = User::factory()->create();
        $user->givePermissionTo(['browse_products', 'access_setting']);
        $this->actingAs($user);

        Livewire::test(ProductTypeConfiguration::class, ['defaultProductType' => ProductType::Standard])
            ->set('hasConfig', true);

        expect(Setting::query()->where('key', 'default_product_type')->value('value'))
            ->toBe(ProductType::Standard->value);
    });
})->group('livewire', 'components', 'products', 'security');
