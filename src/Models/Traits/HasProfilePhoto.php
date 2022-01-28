<?php

namespace Shopper\Framework\Models\Traits;

use Illuminate\Support\Facades\Storage;

trait HasProfilePhoto
{
    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getPictureAttribute(): string
    {
        if ($this->avatar_type === 'storage') {
            return Storage::disk(config('shopper.system.storage.disks.avatars'))->url($this->avatar_location);
        }

        return $this->defaultProfilePhotoUrl();
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl(): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=1d4ed8&background=dbeafe';
    }
}
