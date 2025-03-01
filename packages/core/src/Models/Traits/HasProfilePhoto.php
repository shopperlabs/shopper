<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

trait HasProfilePhoto
{
    protected function picture(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->avatar_type === 'storage'
                ? Storage::disk(config('shopper.media.storage.disk_name'))->url($this->avatar_location)
                : $this->defaultProfilePhotoUrl()
        );
    }

    protected function defaultProfilePhotoUrl(): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=' . config('shopper.admin.avatar.color') . '&background=' . config('shopper.admin.avatar.bg_color');
    }
}
