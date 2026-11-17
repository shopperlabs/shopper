<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Order;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class FileDownloadController
{
    /**
     * Download a purchased file.
     *
     * Only reachable through the signed link issued by
     * `GET /customers/me/orders/{orderId}/items/{itemId}/files/{fileId}`.
     */
    public function __invoke(Request $request, string $fileId): StreamedResponse
    {
        /** @var class-string<Media> $media */
        $media = config('media-library.media_model');

        return $media::query()
            ->where('collection_name', 'files')
            ->where('uuid', $fileId)
            ->firstOrFail()
            ->toResponse($request);
    }
}
