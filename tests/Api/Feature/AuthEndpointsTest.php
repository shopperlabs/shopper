<?php

declare(strict_types=1);

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Sleep;
use Shopper\Models\Permission;
use Shopper\Models\Role;
use Tests\Core\Stubs\User;

uses(Tests\Api\TestCase::class);

it('registers a customer and returns a usable token', function (): void {
    $response = $this->postJson('/store/auth/register', [
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => 'jane@example.com',
        'password' => 'super-secret-password',
    ])->assertCreated()
        ->assertJsonPath('data.type', 'customers')
        ->assertJsonPath('data.attributes.email', 'jane@example.com');

    $token = $response->json('meta.token');
    expect($token)->toBeString()->not->toBeEmpty();

    $user = User::query()->where('email', 'jane@example.com')->firstOrFail();
    expect($user->public_id)->not->toBeNull()
        ->and($user->hasRole(config('shopper.admin.roles.user')))->toBeTrue();

    $this->getJson('/store/customers/me', ['Authorization' => 'Bearer '.$token])
        ->assertOk()
        ->assertJsonPath('data.id', $user->public_id);
});

it('rejects a registration with an already used email', function (): void {
    User::factory()->create(['email' => 'taken@example.com']);

    $this->postJson('/store/auth/register', [
        'last_name' => 'Doe',
        'email' => 'taken@example.com',
        'password' => 'super-secret-password',
    ])->assertUnprocessable();
});

it('logs a customer in and rejects invalid credentials', function (): void {
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('correct-password'),
    ]);

    $token = $this->postJson('/store/auth/login', [
        'email' => 'john@example.com',
        'password' => 'correct-password',
    ])->assertOk()
        ->assertJsonPath('data.id', $user->public_id)
        ->json('meta.token');

    expect($token)->toBeString()->not->toBeEmpty();

    $this->postJson('/store/auth/login', [
        'email' => 'john@example.com',
        'password' => 'wrong-password',
    ])->assertUnprocessable();
});

it('throttles failed logins per email, even with the right password afterwards', function (): void {
    User::factory()->create(['email' => 'john@example.com', 'password' => Hash::make('correct-password')]);
    User::factory()->create(['email' => 'jane@example.com', 'password' => Hash::make('correct-password')]);

    $attempts = (int) config('shopper.http.login_failures');

    for ($attempt = 0; $attempt < $attempts; $attempt++) {
        $this->postJson('/store/auth/login', ['email' => 'Jöhn@Example.com', 'password' => 'wrong'])
            ->assertUnprocessable()
            ->assertJsonPath('errors.0.code', 'credentials_invalid');
    }

    $detail = $this->postJson('/store/auth/login', ['email' => 'john@example.com', 'password' => 'correct-password'])
        ->assertStatus(429)
        ->assertJsonPath('errors.0.code', 'rate_limited')
        ->assertHeader('Retry-After')
        ->json('errors.0.detail');

    expect($detail)->toStartWith('Too many login attempts');

    $this->postJson('/store/auth/login', ['email' => 'jane@example.com', 'password' => 'correct-password'])->assertOk();
});

it('clears the failed login count on a successful login', function (): void {
    User::factory()->create(['email' => 'john@example.com', 'password' => Hash::make('correct-password')]);

    $attempts = (int) config('shopper.http.login_failures');

    for ($round = 0; $round < 2; $round++) {
        for ($attempt = 1; $attempt < $attempts; $attempt++) {
            $this->postJson('/store/auth/login', ['email' => 'john@example.com', 'password' => 'wrong'])->assertUnprocessable();
        }

        $this->postJson('/store/auth/login', ['email' => 'john@example.com', 'password' => 'correct-password'])->assertOk();
    }
});

it('revokes the current token on logout', function (): void {
    User::factory()->create([
        'email' => 'leaving@example.com',
        'password' => Hash::make('correct-password'),
    ]);

    $token = $this->postJson('/store/auth/login', [
        'email' => 'leaving@example.com',
        'password' => 'correct-password',
    ])->json('meta.token');

    $headers = ['Authorization' => 'Bearer '.$token];

    $this->postJson('/store/auth/logout', [], $headers)->assertNoContent();

    $this->app->make('auth')->forgetGuards();

    $this->getJson('/store/customers/me', $headers)->assertUnauthorized();
});

it('sends a reset link without leaking whether the email exists', function (): void {
    Notification::fake();

    $user = User::factory()->create(['email' => 'forgetful@example.com']);

    $this->postJson('/store/auth/forgot-password', ['email' => 'forgetful@example.com'])
        ->assertStatus(202);

    $this->postJson('/store/auth/forgot-password', ['email' => 'unknown@example.com'])
        ->assertStatus(202);

    Notification::assertSentTo($user, ResetPassword::class);
});

it('resets the password and revokes every token', function (): void {
    $user = User::factory()->create([
        'email' => 'resetting@example.com',
        'password' => Hash::make('old-password'),
    ]);

    $staleToken = $user->createToken('store', ['store'])->plainTextToken;
    $resetToken = Password::createToken($user);

    $this->postJson('/store/auth/reset-password', [
        'email' => 'resetting@example.com',
        'token' => $resetToken,
        'password' => 'brand-new-password',
    ])->assertNoContent();

    expect(Hash::check('brand-new-password', $user->fresh()->password))->toBeTrue();

    $this->app->make('auth')->forgetGuards();

    $this->getJson('/store/customers/me', ['Authorization' => 'Bearer '.$staleToken])
        ->assertUnauthorized();

    $this->postJson('/store/auth/reset-password', [
        'email' => 'resetting@example.com',
        'token' => 'invalid-token',
        'password' => 'another-password',
    ])->assertUnprocessable();
});

it('takes a customer from registration through a password reset to a new session', function (): void {
    Notification::fake();

    $token = $this->postJson('/store/auth/register', [
        'last_name' => 'Doe',
        'email' => 'jane@example.com',
        'password' => 'first-password',
    ])->assertCreated()->json('meta.token');

    $this->postJson('/store/auth/logout', [], ['Authorization' => 'Bearer '.$token])->assertNoContent();

    $this->postJson('/store/auth/forgot-password', ['email' => 'jane@example.com'])->assertStatus(202);

    $customer = User::query()->where('email', 'jane@example.com')->firstOrFail();
    $resetToken = null;

    Notification::assertSentTo($customer, ResetPassword::class, function (ResetPassword $notification) use (&$resetToken): bool {
        $resetToken = $notification->token;

        return true;
    });

    $this->postJson('/store/auth/reset-password', [
        'email' => 'jane@example.com',
        'token' => $resetToken,
        'password' => 'second-password',
    ])->assertNoContent();

    $this->postJson('/store/auth/login', ['email' => 'jane@example.com', 'password' => 'first-password'])
        ->assertUnprocessable();

    $token = $this->postJson('/store/auth/login', ['email' => 'jane@example.com', 'password' => 'second-password'])
        ->assertOk()
        ->json('meta.token');

    $this->app->make('auth')->forgetGuards();

    $this->getJson('/store/customers/me', ['Authorization' => 'Bearer '.$token])
        ->assertOk()
        ->assertJsonPath('data.id', $customer->public_id);
});

it('serves customers on a store whose permissions were never migrated', function (): void {
    Permission::findByName('system.dashboard')->delete();

    $this->postJson('/store/auth/register', [
        'last_name' => 'Doe',
        'email' => 'jane@example.com',
        'password' => 'first-password',
    ])->assertCreated();

    $token = $this->postJson('/store/auth/login', ['email' => 'jane@example.com', 'password' => 'first-password'])
        ->assertOk()
        ->json('meta.token');

    $this->app->make('auth')->forgetGuards();

    $this->getJson('/store/customers/me', ['Authorization' => 'Bearer '.$token])->assertOk();
});

it('keeps team members out of the store API', function (string $role): void {
    config(['auth.guards.sanctum.provider' => 'users']);
    Notification::fake();
    Sleep::fake();

    $member = User::factory()->create(['email' => 'staff@example.com', 'password' => Hash::make('correct-password')]);
    $member->assignRole($role);
    $invitation = Password::createToken($member);

    $this->travel(2)->minutes();

    $this->postJson('/store/auth/login', ['email' => 'staff@example.com', 'password' => 'correct-password'])
        ->assertUnprocessable()
        ->assertJsonPath('errors.0.code', 'credentials_invalid');

    $this->postJson('/store/auth/forgot-password', ['email' => 'staff@example.com'])->assertStatus(202);

    Notification::assertNothingSent();
    Sleep::assertSleptTimes(1);

    expect($member->tokens()->count())->toBe(0)
        ->and(Password::tokenExists($member, $invitation))->toBeTrue();

    $storeToken = $member->createToken('store', ['store'])->plainTextToken;

    $this->getJson('/store/customers/me', ['Authorization' => 'Bearer '.$storeToken])->assertUnauthorized();
})->with([
    'administrator' => fn (): string => config('shopper.admin.roles.admin'),
    'role with dashboard access' => fn (): string => Role::create(['name' => 'support'])->givePermissionTo('system.dashboard')->name,
]);
