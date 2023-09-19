<?php

declare(strict_types=1);

namespace Shopper\Framework\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

final class UserCommand extends Command
{
    protected $signature = 'shopper:admin';

    protected $description = 'Create user with admin role and all permissions.';

    public function handle(): void
    {
        $this->info('Create Admin User for Shopper administration panel');
        $this->createUser();
        $this->info('User created successfully.');
    }

    protected function createUser(): void
    {
        $email = $this->ask('Email Address', 'admin@admin.com');
        $first_name = $this->ask('First Name', 'Shopper');
        $last_name = $this->ask('Last Name', 'Admin');
        $password = $this->secret('Password');
        $confirmPassword = $this->secret('Confirm Password');

        if ($password !== $confirmPassword) {
            $this->info('Passwords don\'t match');
        }

        $this->info('Creating admin account...');

        $userData = [
            'email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'password' => Hash::make($password),
            'last_login_at' => now()->toDateTimeString(),
            'email_verified_at' => now()->toDateTimeString(),
            'last_login_ip' => request()->getClientIp(),
        ];
        $model = config('auth.providers.users.model');

        try {
            $user = tap((new $model())->forceFill($userData))->save();

            $user->assignRole(config('shopper.system.users.admin_role'));
        } catch (Exception|QueryException $e) {
            $this->error($e->getMessage());
        }
    }
}
