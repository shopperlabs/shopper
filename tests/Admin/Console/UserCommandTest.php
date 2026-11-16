<?php

declare(strict_types=1);

use Shopper\Models\Role;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

it('fails without creating a user when the administrator role is missing', function (): void {
    Role::query()->where('name', config('shopper.admin.roles.admin'))->delete();

    $users = User::query()->count();

    $this->artisan('shopper:user')->assertFailed();

    expect(User::query()->count())->toBe($users);
})->group('admin', 'console');
