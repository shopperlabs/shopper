<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Shopper\Core\Traits\CanHaveDiscount;
use Shopper\Core\Traits\HasProfilePhoto;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property-read int $id
 * @property-read string|null $first_name
 * @property-read string $last_name
 * @property-read Carbon|null $email_verified_at
 * @property-read Carbon|null $birth_date
 */
class User extends Authenticatable
{
    use CanHaveDiscount;
    use HasFactory;
    use HasProfilePhoto;
    use HasRoles;
    use Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
        'last_login_at',
        'last_login_ip',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'birth_date' => 'datetime',
    ];

    protected $appends = [
        'full_name',
        'picture',
        'roles_label',
        'birth_date_formatted',
    ];

    public static function boot(): void
    {
        parent::boot();

        self::deleting(function ($model): void {
            $model->addresses()->delete();
            $model->roles()->detach();
            $model->orders()->delete();
        });
    }

    public function getTable(): string
    {
        return shopper_table('users');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(config('shopper.core.users.admin_role'));
    }

    public function isVerified(): bool
    {
        return null !== $this->email_verified_at;
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name
            ? $this->first_name . ' ' . $this->last_name
            : $this->last_name;
    }

    public function getBirthDateFormattedAttribute(): string
    {
        if ($this->birth_date) {
            return $this->birth_date->formatLocalized('%d, %B %Y');
        }

        return __('shopper::words.not_defined');
    }

    public function getRolesLabelAttribute(): string
    {
        $roles = $this->roles()->pluck('display_name')->toArray();

        if (count($roles)) {
            return implode(', ', array_map(fn ($item) => ucwords($item), $roles));
        }

        return 'N/A';
    }

    public function scopeResearch(Builder $query, $term): Builder
    {
        return $query->where(
            fn ($query) => $query->where('last_name', 'like', '%' . $term . '%')
                ->orWhere('first_name', 'like', '%' . $term . '%')
                ->orWhere('email', 'like', '%' . $term . '%')
        );
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
