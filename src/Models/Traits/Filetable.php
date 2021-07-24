<?php

namespace Shopper\Framework\Models\Traits;

use Shopper\Framework\Models\System\File;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
