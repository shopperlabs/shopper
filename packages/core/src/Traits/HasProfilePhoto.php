<?php

declare(strict_types=1);

namespace Shopper\Core\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

trait HasProfilePhoto
{
    public function picture(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->avatar_type === 'storage'
                ? Storage::disk(config('shopper.core.storage.disk_name'))->url($this->avatar_location)
                : $this->defaultProfilePhotoUrl()

        );
    }

    protected function defaultProfilePhotoUrl(): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=' . config('shopper.admin.avatar.color') . '&background=' . config('shopper.admin.avatar.bg_color');
    }
}
