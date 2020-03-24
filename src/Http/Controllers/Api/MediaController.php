<?php

namespace Shopper\Framework\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Shopper\Framework\Repositories\MediaRepository;

class MediaController extends Controller
{
    /**
     * @var MediaRepository
     */
    protected MediaRepository $repository;

    public function __construct(MediaRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Upload single file and save to database.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $file = $request->file('file');
        $name = str_slug(explode('.', $file->getClientOriginalName())[0]). '-' .time();
        $filename = $name . '.' . $file->getClientOriginalExtension();

        $file->storeAs('/uploads', $filename, 'public');

        // save file information to database.
        $data = [
            'disk_name'     => $filename,
            'file_name'     => $file->getClientOriginalName(),
            'file_size'     => $file->getSize(),
            'content_type'  => $file->getClientMimeType(),
            'file_url'      => '/uploads/'.$filename,
            'field'         => 'preview_image',
            'is_public'     => true
        ];

        $media = $this->repository->create($data);

        $response = [
            'status'   => 'success',
            'url'      => asset('storage/uploads/'.$filename),
            'id'       => $media->id,
            'name'     => $filename,
        ];

        return response()->json($response);
    }

    /**
     * Remove a media from the database.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove($id)
    {
        $media = $this->repository->getById($id);
        Storage::disk('public')->delete('/uploads/categories/'.$media->disk_name);

        try {
            $media->delete();
            $response = [
                'status'  => 'success',
                'message' => __('File successfully remove'),
            ];

        } catch (\Exception $exception) {
            $response = [
                'status'  => 'error',
                'message' => $exception->getMessage(),
            ];
        }

        return response()->json($response);
    }
}
