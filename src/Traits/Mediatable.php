<?php

namespace Shopper\Framework\Traits;

use Shopper\Framework\Models\Media;

trait Mediatable
{
    /**
     * Get The image preview link.
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string|null
     */
    public function getPreviewImageLinkAttribute()
    {
        if ($this->previewImage) {
            return url('/storage'.$this->previewImage->file_url);
        }

        return null;
    }

    /**
     * Return the current preview image id if exists.
     *
     * @return int|null
     */
    public function getPreviewImageIdAttribute()
    {
        if ($this->previewImage) {
            return $this->previewImage->id;
        }

        return null;
    }

    /**
     * Get Image preview for a record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function previewImage()
    {
        return $this->morphOne(Media::class, 'mediatable')->where('field', 'preview_image');
    }

    /**
     * Get all images of a record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images()
    {
        return $this->morphMany(Media::class, 'mediatable')->where('field', 'images');
    }
}
