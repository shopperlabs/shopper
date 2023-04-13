<?php

declare(strict_types=1);

namespace Shopper\Framework\Traits;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait WithUploadProcess
{
    public array $files = [];

    public function removeMedia(int $id): void
    {
        Media::find($id)->delete();

        $this->emitSelf('mediaDeleted');

        $this->notify([
            'title' => __('Removed'),
            'message' => __('Media removed from the storage.'),
        ]);
    }
}
