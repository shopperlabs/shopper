<?php

namespace Shopper\Framework\Traits;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait WithUploadProcess
{
    public $files = [];

    public function removeMedia(int $id)
    {
        Media::find($id)->delete();

        $this->emitSelf('mediaDeleted');

        $this->notify([
            'title' => __('Removed'),
            'message' => __('Media removed from the storage.'),
        ]);
    }
}
