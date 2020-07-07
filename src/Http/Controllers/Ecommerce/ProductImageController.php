<?php

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Shopper\Framework\Repositories\MediaRepository;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class ProductImageController extends Controller
{
    /**
     * @var ProductRepository
     */
    protected ProductRepository $productRepository;

    /**
     * @var MediaRepository
     */
    protected MediaRepository $mediaRepository;

    /**
     * ProductImageController new instance.
     *
     * @param  ProductRepository  $productRepository
     * @param  MediaRepository  $mediaRepository
     */
    public function __construct(ProductRepository $productRepository, MediaRepository $mediaRepository)
    {
        $this->productRepository = $productRepository;
        $this->mediaRepository = $mediaRepository;
    }

    /**
     * Get Product's images.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(int $id)
    {
        $product = $this->productRepository->with('images')->getById($id);

        return response()->json([
            'images' => $product->images,
        ]);
    }

    /**
     * Store a new image for a product.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, int $id)
    {
        $image = $request->file('image');
        $filename = $image->store('/', config('shopper.storage.disks.uploads'));

        // save file information to database.
        $media = $this->mediaRepository->create([
            'disk_name'     => $filename,
            'file_name'     => $image->getClientOriginalName(),
            'file_size'     => $image->getSize(),
            'content_type'  => $image->getClientMimeType(),
            'file_url'      => $filename,
            'field'         => 'images',
            'is_public'     => true
        ]);

        $media->update([
            'mediatable_type' => config('shopper.models.product'),
            'mediatable_id'   => $id,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => __("Successfully uploaded"),
            'url'     => Storage::disk(config('shopper.storage.disks.uploads'))->url($filename),
        ]);
    }

    public function delete(int $id)
    {
    }
}
