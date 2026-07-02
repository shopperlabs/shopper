<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Shopper\Core\Events\Customers\CustomerRegistered;

final class RegisterCustomerAction
{
    public function __construct(
        private readonly DatabaseManager $database,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Model&Authenticatable
    {
        return $this->database->transaction(function () use ($data): Model&Authenticatable {
            /** @var class-string<Model&Authenticatable> $model */
            $model = (string) config('auth.providers.users.model');

            /** @var Model&Authenticatable $user */
            $user = $model::query()->create([
                'first_name' => $data['first_name'] ?? null,
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'opt_in' => (bool) ($data['opt_in'] ?? false),
            ]);

            if (method_exists($user, 'assignRole')) {
                $user->assignRole((string) config('shopper.admin.roles.user', 'user'));
            }

            event(new CustomerRegistered($user));

            return $user;
        });
    }
}
