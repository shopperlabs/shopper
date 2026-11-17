<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Account;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Shopper\Api\Concerns\NamesProductFiles;
use Shopper\Core\Models\Contracts\Order as OrderContract;
use Shopper\Core\Models\Contracts\Product as ProductContract;
use Shopper\Core\Models\Order;
use Shopper\Http\Enum\ErrorCode;
use Shopper\Http\Exceptions\ApiException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

final class OrderFileController
{
    use NamesProductFiles;

    private const int LINK_LIFETIME_IN_MINUTES = 5;

    /**
     * Get a temporary download link for a purchased file.
     *
     * The link is valid for five minutes and only issued while the order is
     * paid or partially refunded. Request a new one for every download.
     */
    public function __invoke(Request $request, string $orderId, string $itemId, string $fileId): JsonResponse
    {
        /** @var Order $order */
        $order = resolve(OrderContract::class)::query()
            ->where('customer_id', $request->user()?->getAuthIdentifier())
            ->where('public_id', $orderId)
            ->firstOrFail();

        $product = $order->items()->where('public_id', $itemId)->firstOrFail()->product;

        $file = $product instanceof ProductContract && $product->isVirtual() && method_exists($product, 'getMedia')
            ? $product->getMedia('files')->firstWhere('uuid', $fileId)
            : null;

        if (! $file instanceof Media) {
            throw (new ModelNotFoundException)->setModel(Media::class, [$fileId]);
        }

        if (! $order->allowsDownloads()) {
            throw new ApiException(Response::HTTP_FORBIDDEN, ErrorCode::Forbidden, __('shopper-api::messages.order.files_unavailable'));
        }

        $expiresAt = now()->addMinutes(self::LINK_LIFETIME_IN_MINUTES);

        $url = config("filesystems.disks.{$file->disk}.driver") === 's3'
            ? $file->getTemporaryUrl($expiresAt, options: [
                'ResponseContentDisposition' => $this->contentDisposition($file),
            ])
            : URL::temporarySignedRoute('shopper.store.files.download', $expiresAt, ['fileId' => $file->uuid]);

        return response()->json([
            'data' => [
                'url' => $url,
                'expires_at' => $expiresAt->toIso8601String(),
            ],
        ]);
    }
}
