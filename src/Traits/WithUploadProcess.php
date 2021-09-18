<?php

namespace Shopper\Framework\Traits;

use Exception;
use Shopper\Framework\Models\System\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait WithUploadProcess
{
    public $file;
    public $files = [];

    public function removeSingleMediaPlaceholder()
    {
        $this->file = null;
    }

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
