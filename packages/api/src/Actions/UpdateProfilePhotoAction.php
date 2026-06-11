<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;
use RuntimeException;

final readonly class UpdateProfilePhotoAction
{
    public function __construct(
        private DatabaseManager $database,
        private FilesystemManager $storage,
    ) {}

    /**
     * Stores the photo on the configured media disk and points the customer
     * at it (avatar_type "storage"), mirroring the admin panel behaviour.
     * The previous stored photo is removed once the swap is persisted.
     */
    public function execute(Model&Authenticatable $customer, UploadedFile $file): void
    {
        $disk = (string) config('shopper.media.storage.disk_name', 'public');

        $this->database->transaction(function () use ($customer, $file, $disk): void {
            $previous = $customer->getAttribute('avatar_type') === 'storage'
                ? $customer->getAttribute('avatar_location')
                : null;

            $path = $file->store('avatars', $disk);

            if ($path === false) {
                throw new RuntimeException('Unable to store the profile photo on the media disk.');
            }

            $customer->forceFill([
                'avatar_type' => 'storage',
                'avatar_location' => $path,
            ])->save();

            if (filled($previous)) {
                $this->storage->disk($disk)->delete((string) $previous);
            }
        });
    }
}
