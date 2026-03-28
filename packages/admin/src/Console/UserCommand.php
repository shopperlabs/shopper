<?php

declare(strict_types=1);

namespace Shopper\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\info;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

final class UserCommand extends Command
{
    protected $signature = 'shopper:user';

    protected $description = 'Create user with admin role and all permissions.';

    public function handle(): void
    {
        info('Create Admin User for Shopper administration panel');
        $this->createUser();
        info('User created successfully.');
    }

    protected function createUser(): void
    {
        $userModel = config('auth.providers.users.model');

        $email = text(
            label: 'Your Email Address',
            placeholder: 'admin@laravelshopper.dev',
            required: true,
            validate: fn (string $value): ?string => $userModel::query()->where('email', $value)->exists()
                    ? 'A user with that email already exists.'
                    : null,
        );

        $first_name = text(
            label: 'What is your First Name',
            placeholder: 'Shopper',
            required: true,
        );

        $last_name = text(
            label: 'What is your Last Name',
            placeholder: 'User',
            required: true,
        );

        $password = password(
            label: 'Choose a Password',
            required: true,
            validate: fn (string $value): ?string => match (true) {
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

        try {
            $user = tap((new $userModel)->forceFill($userData))->save(); // @phpstan-ignore-line

            $user->assignRole(config('shopper.admin.roles.admin'));
        } catch (Exception|QueryException $e) {
            $this->error($e->getMessage());
        }
    }
}
