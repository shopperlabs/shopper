<?php

namespace Shopper\Framework\Models\Traits;

use Illuminate\Support\Facades\Storage;
use Shopper\Framework\Models\System\File;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Filetable
{
    public function getFirstImage(): ?File
    {
        return $this->files->first();
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'filetable');
    }

    protected static function booted()
    {
        static::deleting(function ($model) {
            if ($model->files->isNotEmpty()) {
                foreach ($model->files as $file) {
                    Storage::disk(config('shopper.system.storage.disks.uploads'))->delete($file->disk_name);
                }
                $model->files()->delete();
            }
        });
    }
}
