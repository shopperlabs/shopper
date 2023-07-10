<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property string $last_name
 * @property string|null $first_name
 * @property bool $is_default
 */
class Address extends Model
{
    use HasFactory;

    public const TYPE_BILLING = 'billing';

    public const TYPE_SHIPPING = 'shipping';

    protected $fillable = [
        'last_name',
        'first_name',
        'company_name',
        'street_address',
        'street_address_plus',
        'zipcode',
        'city',
        'phone_number',
        'is_default',
        'type',
        'user_id',
        'country_id',
    ];

    protected $appends = [
        'full_name',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected $with = [
        'country',
    ];

    protected static function boot(): void
    {
        parent::boot();

        self::creating(function ($address): void {
            if ($address->is_default) {
                $address->user->addresses()->where('type', $address->type)->update([
                    'is_default' => false,
                ]);
            }
        });
    }

    public function getTable(): string
    {
        return shopper_table('user_addresses');
    }

    public function getFullNameAttribute(): string
    {
        return $this->last_name
            ? $this->first_name . ' ' . $this->last_name
            : $this->first_name;
    }

    public function isDefault(): bool
    {
        return true === $this->is_default;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', User::class), 'user_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
