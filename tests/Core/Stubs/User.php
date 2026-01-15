<?php

declare(strict_types=1);

namespace Tests\Core\Stubs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Shopper\Core\Models\Contracts\ShopperUser as ShopperUserInterface;
use Shopper\Core\Traits\ShopperUser;

class User extends Authenticatable implements ShopperUserInterface
{
    use HasFactory;
    use Notifiable;
    use ShopperUser;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
        'last_login_at',
        'last_login_ip',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
