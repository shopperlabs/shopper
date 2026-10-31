<?php

declare(strict_types=1);

use Illuminate\Foundation\Auth\User as Authenticatable;
use Livewire\Livewire;
use Shopper\Livewire\Pages\Account;
use Shopper\Models\Contracts\ShopperUser;
use Shopper\Traits\InteractsWithShopper;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe(Account::class, function (): void {
    it('can render account component', function (): void {
        Livewire::test(Account::class)
            ->assertOk()
            ->assertViewIs('shopper::livewire.pages.account');
    });

    it('renders without the two factor section when the user does not implement its contract', function (): void {
        config()->set('shopper.auth.2fa_enabled', true);

        $user = (new class extends Authenticatable implements ShopperUser
        {
            use InteractsWithShopper;

            protected $table = 'users';
        })->newQuery()->findOrFail($this->user->id);

        $this->actingAs($user, config('shopper.auth.guard'));

        Livewire::test(Account::class)
            ->assertOk()
            ->assertDontSee(__('shopper::pages/auth.account.two_factor_title'));
    });
});
