<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Shopper\Api\Concerns\NamesProductFiles;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class FileDownloadController
{
    use NamesProductFiles;

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

        $file = $media::query()
            ->where('collection_name', 'files')
            ->where('uuid', $fileId)
            ->firstOrFail();

        return Storage::disk($file->disk)->download($file->getPathRelativeToRoot(), headers: [
            'Content-Disposition' => $this->contentDisposition($file),
        ]);
    }
}
