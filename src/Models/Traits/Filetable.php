<?php

namespace Shopper\Framework\Models\Traits;

use Shopper\Framework\Models\System\File;

trait Filetable
{
    /**
     * Return the first image of the current Model.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getFirstImage()
    {
        return $this->files()->first();
    }

    /**
     * Get all files of a Model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function files()
    {
        return $this->morphMany(File::class, 'filetable');
    }
}
