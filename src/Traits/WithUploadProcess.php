<?php

declare(strict_types=1);

namespace Shopper\Framework\Traits;

use Filament\Notifications\Notification;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait WithUploadProcess
{
    public $files = [];

    public function removeMedia(int $id): void
    {
        Media::query()->find($id)->delete();

        $this->emitSelf('mediaDeleted');

        Notification::make()
            ->title(__('Removed'))
            ->body(__('Media removed from the storage.'))
            ->success()
            ->send();
    }
}
