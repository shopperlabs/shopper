<?php

namespace Shopper\Framework\Traits;

use Shopper\Framework\Models\Media;

trait Mediatable
{
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
