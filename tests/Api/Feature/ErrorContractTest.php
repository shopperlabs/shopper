<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tests\Core\Stubs\User;

uses(Tests\Api\TestCase::class);

it('names the failed rule as the error code of a validation failure', function (): void {
    User::factory()->create(['email' => 'taken@example.com']);

    $this->postJson('/store/auth/register', [
        'last_name' => 'Doe',
        'email' => 'taken@example.com',
        'password' => 'super-secret-password',
    ])
        ->assertUnprocessable()
        ->assertJsonPath('errors.0.code', 'unique')
        ->assertJsonPath('errors.0.source.pointer', '/data/attributes/email');
});

it('answers a stable code when the credentials are refused', function (): void {
    User::factory()->create(['email' => 'john@example.com', 'password' => Hash::make('correct-password')]);

    $this->postJson('/store/auth/login', ['email' => 'john@example.com', 'password' => 'wrong'])
        ->assertUnprocessable()
        ->assertJsonPath('errors.0.code', 'credentials_invalid')
        ->assertJsonPath('errors.0.source.pointer', '/data/attributes/email');
});

it('answers a stable code on an invalid password reset token without revealing whether the email exists', function (): void {
    User::factory()->create(['email' => 'john@example.com']);

    $known = $this->postJson('/store/auth/reset-password', [
        'email' => 'john@example.com',
        'token' => 'not-a-token',
        'password' => 'brand-new-password',
    ])
        ->assertUnprocessable()
        ->assertJsonPath('errors.0.code', 'reset_token_invalid');

    $unknown = $this->postJson('/store/auth/reset-password', [
        'email' => 'nobody@example.com',
        'token' => 'not-a-token',
        'password' => 'brand-new-password',
    ])
        ->assertUnprocessable()
        ->assertJsonPath('errors.0.code', 'reset_token_invalid');

    expect($unknown->json('errors.0.detail'))->toBe($known->json('errors.0.detail'));
});

it('pairs each message of a field with the rule it failed', function (): void {
    $errors = collect($this->postJson('/store/auth/register', [
        'last_name' => 'Doe',
        'email' => str_repeat('a', 260),
        'password' => 'super-secret-password',
    ])->assertUnprocessable()->json('errors'))
        ->where('source.pointer', '/data/attributes/email')
        ->pluck('code')
        ->values()
        ->all();

    expect($errors)->toBe(['email', 'max']);
});

it('keeps the rate limit headers on a throttled request', function (): void {
    $attempts = (int) config('shopper.http.rate_limiters.shopper-api-auth');

    for ($attempt = 0; $attempt < $attempts; $attempt++) {
        $this->postJson('/store/auth/login', ['email' => "john{$attempt}@example.com", 'password' => 'wrong'])->assertUnprocessable();
    }

    $this->postJson('/store/auth/login', ['email' => 'john@example.com', 'password' => 'wrong'])
        ->assertStatus(Response::HTTP_TOO_MANY_REQUESTS)
        ->assertJsonPath('errors.0.code', 'rate_limited')
        ->assertHeader('Retry-After');
});

it('never names the model class in a not found detail', function (): void {
    $response = $this->getJson('/store/products/unknown-slug')
        ->assertNotFound()
        ->assertJsonPath('errors.0.code', 'not_found');

    expect($response->json('errors.0.detail'))->not->toContain('Shopper\\');
});

it('rejects an expired token', function (): void {
    $token = $this->postJson('/store/auth/register', [
        'last_name' => 'Doe',
        'email' => 'jane@example.com',
        'password' => 'super-secret-password',
    ])->assertCreated()->json('meta.token');

    $this->travelTo(now()->addMinutes((int) config('shopper.api.token_expiration') + 1));

    $this->getJson('/store/customers/me', ['Authorization' => 'Bearer '.$token])
        ->assertUnauthorized()
        ->assertJsonPath('errors.0.code', 'unauthenticated')
        ->assertJsonPath('errors.0.status', '401');
});

it('names the password rule as the error code on a weak password', function (): void {
    $this->postJson('/store/auth/register', ['last_name' => 'Doe', 'email' => 'jane@example.com', 'password' => '123'])
        ->assertUnprocessable()
        ->assertJsonPath('errors.0.code', 'password')
        ->assertJsonPath('errors.0.source.pointer', '/data/attributes/password');
});

it('writes a nested field as JSON pointer segments', function (): void {
    $this->getJson('/store/products?filter[currency]=NOPE')
        ->assertUnprocessable()
        ->assertJsonPath('errors.0.code', 'currency_unknown')
        ->assertJsonPath('errors.0.source.pointer', '/data/attributes/filter/currency');
});

it('never expires a token when no expiration is configured', function (): void {
    config(['shopper.api.token_expiration' => null]);

    $token = $this->postJson('/store/auth/register', [
        'last_name' => 'Doe',
        'email' => 'jane@example.com',
        'password' => 'super-secret-password',
    ])->assertCreated()->json('meta.token');

    $this->travelTo(now()->addYears(5));

    $this->getJson('/store/customers/me', ['Authorization' => 'Bearer '.$token])->assertOk();
});
