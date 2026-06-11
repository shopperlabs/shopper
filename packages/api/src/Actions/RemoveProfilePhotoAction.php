<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemManager;

final readonly class RemoveProfilePhotoAction
{
    public function __construct(
        private DatabaseManager $database,
        private FilesystemManager $storage,
    ) {}

    /**
     * Deletes the stored photo and falls back to the generated avatar
     * (avatar_type "avatar_ui"), like the admin panel.
     */
    public function execute(Model&Authenticatable $customer): void
    {
        $disk = (string) config('shopper.media.storage.disk_name', 'public');

        $this->database->transaction(function () use ($customer, $disk): void {
            $previous = $customer->getAttribute('avatar_type') === 'storage'
                ? $customer->getAttribute('avatar_location')
                : null;

            $customer->forceFill([
                'avatar_type' => 'avatar_ui',
                'avatar_location' => null,
            ])->save();

            if (filled($previous)) {
                $this->storage->disk($disk)->delete((string) $previous);
            }
        });
    }
}
