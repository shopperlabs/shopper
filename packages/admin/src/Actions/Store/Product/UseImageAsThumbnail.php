<?php

declare(strict_types=1);

namespace Shopper\Actions\Store\Product;

use Illuminate\Auth\Access\AuthorizationException;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class UseImageAsThumbnail
{
    public function __invoke(HasMedia $model, int|string $mediaId): Media
    {
        $media = $model->getMedia((string) config('shopper.media.storage.collection_name', 'uploads'))
            ->firstWhere('id', (int) $mediaId);

        if (! $media instanceof Media) {
            throw new AuthorizationException;
        }

        return $media->copy(
            $model,
            (string) config('shopper.media.storage.thumbnail_collection', 'thumbnail'),
            (string) config('shopper.media.storage.disk_name', 'public'),
        );
    }
}
