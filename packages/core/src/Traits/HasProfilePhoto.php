<?php

declare(strict_types=1);

namespace Shopper\Core\Traits;

use Illuminate\Support\Facades\Storage;

trait HasProfilePhoto
{
    public function getPictureAttribute(): string
    {
        if ($this->avatar_type === 'storage') {
            return Storage::disk(config('shopper.core.storage.disk_name'))->url($this->avatar_location);
        }

        return $this->defaultProfilePhotoUrl();
    }

    protected function defaultProfilePhotoUrl(): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=' . config('shopper.admin.avatar.color') . '&background=' . config('shopper.admin.avatar.bg_color');
    }
}
