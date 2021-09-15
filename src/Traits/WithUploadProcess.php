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

    /**
     * Removed file from the database.
     *
     * @throws Exception
     */
    public function deleteImage(int $id)
    {
        File::query()->find($id)->delete();

        $this->emitSelf('fileDeleted');

        $this->notify([
            'title' => __('Removed'),
            'message' => __('Image removed from the storage.'),
        ]);
    }

    /**
     * Upload file and associate with the current model.
     */
    public function uploadFile(string $model, int $id)
    {
        File::query()->create([
            'disk_name' => $this->file->store('/', config('shopper.system.storage.disks.uploads')),
            'file_name' => $this->file->getClientOriginalName(),
            'file_size' => $this->file->getSize(),
            'content_type' => $this->file->getClientMimeType(),
            'filetable_type' => $model,
            'filetable_id' => $id,
        ]);
    }
}
