<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Database\Factories\PaymentMethodFactory;
use Shopper\Core\Models\Contracts\PaymentMethod as PaymentMethodContract;
use Shopper\Core\Models\Traits\HasPublicId;
use Shopper\Core\Models\Traits\HasSlug;
use Shopper\Core\Models\Traits\HasZones;

/**
 * @property-read int $id
 * @property-read ?string $public_id
 * @property-read string $title
 * @property-read string $slug
 * @property-read bool $is_enabled
 * @property-read ?string $driver
 * @property-read ?string $description
 * @property-read ?string $link_url
 * @property-read ?string $instructions
 * @property-read array<string, mixed>|null $metadata
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
class PaymentMethod extends Model implements PaymentMethodContract
{
    /** @use HasFactory<PaymentMethodFactory> */
    use HasFactory;

    use HasPublicId;
    use HasSlug;
    use HasZones;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('payment_methods');
    }

    public function logo(): ?string
    {
        if (! app()->bound('shopper.payment.logo')) {
            return null;
        }

        /** @var callable(static): ?string $resolver */
        $resolver = app('shopper.payment.logo');

        return $resolver($this);
    }

    protected static function newFactory(): PaymentMethodFactory
    {
        return PaymentMethodFactory::new();
    }

    /**
     * @param  Builder<PaymentMethod>  $query
     * @return Builder<PaymentMethod>
     */
    #[Scope]
    protected function enabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
        ];
    }
}
