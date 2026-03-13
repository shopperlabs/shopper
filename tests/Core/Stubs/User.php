<?php

declare(strict_types=1);

namespace Tests\Core\Stubs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Shopper\Contracts\HasStoreAuthentication;
use Shopper\Contracts\HasStoreAuthenticationRecovery;
use Shopper\Models\Contracts\ShopperUser;
use Shopper\Traits\InteractsWithShopper;
use Shopper\Traits\InteractsWithStoreAuthentication;
use Shopper\Traits\InteractsWithStoreAuthenticationRecovery;

class User extends Authenticatable implements HasStoreAuthentication, HasStoreAuthenticationRecovery, ShopperUser
{
    use HasFactory;
    use InteractsWithShopper;
    use InteractsWithStoreAuthentication;
    use InteractsWithStoreAuthenticationRecovery;
    use Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
        'last_login_at',
        'last_login_ip',
        'store_two_factor_recovery_codes',
        'store_two_factor_secret',
    ];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
