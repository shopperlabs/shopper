<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Spatie\Permission\Models\Role as Model;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $guard_name
 * @property-read bool $can_be_removed
 * @property-read string $display_name
 * @property-read string|null $description
 */
class Role extends Model
{
    public function isAdmin(): bool
    {
        return $this->name === config('shopper.core.roles.admin');
    }

    protected function casts(): array
    {
        return [
            'can_be_removed' => 'boolean',
        ];
    }
}
