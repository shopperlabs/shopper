<?php

declare(strict_types=1);

namespace Shopper\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Shopper\Core\Models\User;

use function Laravel\Prompts\info;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

final class UserCommand extends Command
{
    protected $signature = 'shopper:admin';

    protected $description = 'Create user with admin role and all permissions.';

    public function handle(): void
    {
        info('Create Admin User for Shopper administration panel');
        $this->createUser();
        info('User created successfully.');
    }

    protected function createUser(): void
    {
        $email = text(
            label: 'Your Email Address',
            default: 'admin@shopper.dev',
            required: true,
            validate: fn (string $value) => User::where('email', $value)->exists()
                    ? 'An admin with that email already exists.'
                    : null,
        );
        $first_name = text(
            label: 'What is your First Name',
            default: 'Shopper',
            required: true,
        );
        $last_name = text(
            label: 'What is your Last Name',
            default: 'User',
            required: true,
        );
        $password = password(
            label: 'Choose a Password',
            required: true,
            validate: fn (string $value) => match (true) {
                mb_strlen($value) < 6 => 'The password must be at least 6 characters.',
                default => null
            },
            hint: 'Minimum 6 characters.'
        );

        info('Creating admin account...');

        $userData = [
            'email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'password' => Hash::make($password),
            'last_login_at' => now()->toDateTimeString(),
            'email_verified_at' => now()->toDateTimeString(),
            'last_login_ip' => request()->getClientIp(),
        ];

        $model = config('auth.providers.users.model', User::class);

        try {
            $user = tap((new $model())->forceFill($userData))->save(); // @phpstan-ignore-line

            $user->assignRole(config('shopper.core.users.admin_role'));
        } catch (Exception | QueryException $e) {
            $this->error($e->getMessage());
        }
    }
}
