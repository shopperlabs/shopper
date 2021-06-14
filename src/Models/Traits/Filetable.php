<?php

namespace Shopper\Framework\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Shopper\Framework\Models\System\File;

trait Filetable
{
    public function getFirstImage(): ?File
    {
        return $this->files()->first();
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'filetable');
    }
}
