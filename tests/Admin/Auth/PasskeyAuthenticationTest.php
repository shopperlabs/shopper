<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Laravel\Passkeys\Actions\VerifyPasskey;
use Livewire\Livewire;
use ParagonIE\ConstantTime\Base64UrlSafe;
use Shopper\Livewire\Pages\Auth\Login;
use Tests\Core\Stubs\User;

uses(Tests\Admin\PasskeysTestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
});

function fakeAssertionCredential(): array
{
    $rawId = random_bytes(16);
    $clientDataJson = json_encode([
        'type' => 'webauthn.get',
        'challenge' => Base64UrlSafe::encodeUnpadded(random_bytes(32)),
        'origin' => config('app.url'),
    ]);

    return [
        'id' => Base64UrlSafe::encodeUnpadded($rawId),
        'rawId' => Base64UrlSafe::encodeUnpadded($rawId),
        'type' => 'public-key',
        'response' => [
            'clientDataJSON' => Base64UrlSafe::encodeUnpadded($clientDataJson),
            'authenticatorData' => Base64UrlSafe::encodeUnpadded(str_repeat("\0", 32).chr(0x01).pack('N', 0)),
            'signature' => Base64UrlSafe::encodeUnpadded(random_bytes(32)),
        ],
    ];
}

describe('Passkey login', function (): void {
    it('keeps the vendor passkey routes disabled', function (): void {
        expect(Route::has('passkey.login'))->toBeFalse()
            ->and(Route::has('passkey.store'))->toBeFalse();
    });

    it('shows the passkey button on the login page', function (): void {
        Livewire::test(Login::class)
            ->assertSee(__('shopper::pages/auth.login.passkey_action'));
    });

    it('serves login options and stores the challenge in session', function (): void {
        $this->get(route('shopper.passkeys.login-options'))
            ->assertOk()
            ->assertJsonStructure(['options' => ['challenge', 'rpId', 'userVerification']])
            ->assertSessionHas('passkey.verification_options');
    });

    it('rejects a malformed login credential', function (): void {
        $this->postJson(route('shopper.passkeys.login'), [
            'credential' => ['id' => 'foo'],
        ])->assertUnprocessable();

        $this->assertGuest(config('shopper.auth.guard'));
    });

    it('signs in a two-factor user with a passkey without a totp challenge', function (): void {
        config()->set('shopper.auth.2fa_enabled', true);

        $this->user->forceFill(['store_two_factor_secret' => encrypt('secret')])->save();

        $passkey = $this->user->passkeys()->create([
            'name' => 'MacBook Pro',
            'credential_id' => uniqid('credential-', true),
            'credential' => ['type' => 'public-key'],
        ]);

        $this->get(route('shopper.passkeys.login-options'))->assertOk();

        $this->mock(VerifyPasskey::class)
            ->shouldReceive('__invoke')
            ->once()
            ->andReturn($passkey);

        $this->postJson(route('shopper.passkeys.login'), [
            'credential' => fakeAssertionCredential(),
        ])
            ->assertOk()
            ->assertJson(['redirect' => route('shopper.dashboard')]);

        $this->assertAuthenticatedAs($this->user, config('shopper.auth.guard'));
    });

    it('rejects a login credential without a pending challenge', function (): void {
        $this->postJson(route('shopper.passkeys.login'), [
            'credential' => [
                'id' => 'foo',
                'rawId' => 'foo',
                'type' => 'public-key',
                'response' => ['clientDataJSON' => 'foo'],
            ],
        ])->assertUnprocessable();

        $this->assertGuest(config('shopper.auth.guard'));
    });
});

describe('Passkey registration endpoints', function (): void {
    it('requires authentication', function (): void {
        $this->getJson(route('shopper.passkeys.registration-options'))
            ->assertUnauthorized();
    });

    it('requires a recently confirmed password', function (): void {
        $this->actingAs($this->user, config('shopper.auth.guard'))
            ->getJson(route('shopper.passkeys.registration-options'))
            ->assertStatus(423);
    });

    it('serves registration options when the password was confirmed', function (): void {
        $this->actingAs($this->user, config('shopper.auth.guard'))
            ->withSession(['auth.password_confirmed_at' => time()])
            ->getJson(route('shopper.passkeys.registration-options'))
            ->assertOk()
            ->assertJsonStructure(['options' => ['challenge', 'rp', 'user']])
            ->assertSessionHas('passkey.registration_options');
    });

    it('rejects a registration credential without a pending challenge', function (): void {
        $this->actingAs($this->user, config('shopper.auth.guard'))
            ->withSession(['auth.password_confirmed_at' => time()])
            ->postJson(route('shopper.passkeys.store'), [
                'name' => 'MacBook Pro',
                'credential' => [
                    'id' => 'foo',
                    'rawId' => 'foo',
                    'type' => 'public-key',
                    'response' => ['clientDataJSON' => 'foo'],
                ],
            ])->assertUnprocessable();

        expect($this->user->passkeys()->count())->toBe(0);
    });
});
