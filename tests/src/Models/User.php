<?php

declare(strict_types=1);

namespace Shopper\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Shopper\Core\Models\User as Authenticatable;
use Shopper\Tests\Database\Factories\UserFactory;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
