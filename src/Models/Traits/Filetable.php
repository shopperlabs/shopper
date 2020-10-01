<?php

namespace Shopper\Framework\Models\Traits;

use Shopper\Framework\Models\System\File;

trait Filetable
{
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
