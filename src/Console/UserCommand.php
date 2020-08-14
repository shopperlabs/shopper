<?php

namespace Shopper\Framework\Console;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shopper:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user with admin role and all permissions.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('Create Admin User for Shopper administration panel');
        $this->createUser();
        $this->info('User created successfully.');
    }

    /**
     * Create admin user.
     *
     * @return void
     */
    protected function createUser(): void
    {
        $email           = $this->ask('Email Address', 'admin@admin.com');
        $first_name      = $this->ask('First Name', 'Admin');
        $last_name       = $this->ask('Last Name', 'Istrator');
        $password        = $this->secret('Password');
        $confirmPassword = $this->secret('Confirm Password');

        // Passwords don't match
        if ($password != $confirmPassword) {
            $this->info('Passwords don\'t match');
        }

        $this->info('Creating admin account...');

        $ip_address = request()->getClientIp();

        $userData = [
            'email'        => $email,
            'first_name'   => $first_name,
            'last_name'    => $last_name,
            'password'     => Hash::make($password),
            'last_login_at'     => now()->toDateTimeString(),
            'email_verified_at' => now()->toDateTimeString(),
            'is_superuser'      => true,
            'last_login_ip' => $ip_address
        ];
        $model = config('auth.providers.users.model');

        try {
            $user = tap((new $model)->forceFill($userData))->save();

            $user->assignRole(config('shopper.users.admin_role'));
        } catch (\Exception | QueryException $e) {
            $this->error($e->getMessage());
        }
    }
}
