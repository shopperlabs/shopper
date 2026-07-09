<?php

declare(strict_types=1);

namespace Shopper\Api\Concerns;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait SerializesMedia
{
    /**
     * Every media in the default (images) collection.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function imagesPayload(): array
    {
        return $this->mediaPayload((string) config('shopper.media.storage.collection_name', 'default'));
    }

    /**
     * The thumbnail media, optionally falling back to the first gallery image.
     *
     * @return array<string, mixed>|null
     */
    protected function thumbnailPayload(bool $withFallback = false): ?array
    {
        if ($withFallback && method_exists($this->resource, 'getThumbnail')) {
            $media = $this->resource->getThumbnail();

            return $media instanceof Media ? $this->mediaToArray($media) : null;
        }

        return $this->firstMediaPayload((string) config('shopper.media.storage.thumbnail_collection', 'thumbnail'));
    }

    /**
     * Every media in the files collection.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function filesPayload(): array
    {
        return $this->mediaPayload('files');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function mediaPayload(string $collection): array
    {
        if (! method_exists($this->resource, 'getMedia')) {
            return [];
        }

        return $this->resource->getMedia($collection)
            ->map(fn (Media $media): array => $this->mediaToArray($media))
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>|null
     */
    private function firstMediaPayload(string $collection): ?array
    {
        if (! method_exists($this->resource, 'getFirstMedia')) {
            return null;
        }

        $media = $this->resource->getFirstMedia($collection);

        return $media instanceof Media ? $this->mediaToArray($media) : null;
    }

    /**
     * @return array<string, mixed>
     */
    private function mediaToArray(Media $media): array
    {
        return [
            'id' => $media->uuid,
            'url' => $media->getFullUrl(),
            'name' => $media->name,
            'extension' => $media->extension,
            'created_at' => $media->created_at?->toIso8601String(),
            'updated_at' => $media->updated_at?->toIso8601String(),
        ];
    }
}
