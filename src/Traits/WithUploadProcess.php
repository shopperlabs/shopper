<?php

namespace Shopper\Framework\Traits;

use Shopper\Framework\Models\System\File;

trait WithUploadProcess
{
    /**
     * Upload file.
     *
     * @var \Illuminate\Http\UploadedFile
     */
    public $file;

    /**
     * Remove file.
     *
     * @return void
     */
    public function removeImage()
    {
        $this->file = null;
    }

    /**
     * Removed file from the database.
     *
     * @param  int  $id
     *
     * @throws \Exception
     *
     * @return void
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
     *
     * @param  string  $model
     * @param  int  $id
     *
     * @return void
     */
    public function uploadFile(string $model, int $id)
    {
        File::query()->create([
            'disk_name' => $filename = $this->file->store('/', config('shopper.system.storage.disks.uploads')),
            'file_name' => $this->file->getClientOriginalName(),
            'file_size' => $this->file->getSize(),
            'content_type' => $this->file->getClientMimeType(),
            'filetable_type' => $model,
            'filetable_id' => $id,
        ]);
    }
}
