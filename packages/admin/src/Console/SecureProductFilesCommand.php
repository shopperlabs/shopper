<?php

declare(strict_types=1);

namespace Shopper\Console;

use Illuminate\Console\Command;
use Shopper\Core\Models\Contracts\Product;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'shopper:files:secure')]
final class SecureProductFilesCommand extends Command
{
    protected $signature = 'shopper:files:secure';

    protected $description = 'Move the files of virtual products to the disk set in "shopper.media.storage.files_disk"';

    public function handle(): int
    {
        $disk = (string) config('shopper.media.storage.files_disk', 'local');

        /** @var class-string<Media> $media */
        $media = config('media-library.media_model');

        $moved = 0;

        $media::query()
            ->where('collection_name', 'files')
            ->where('model_type', resolve(Product::class)->getMorphClass())
            ->where('disk', '!=', $disk)
            ->with('model')
            ->lazyById()
            ->each(function (Media $file) use ($disk, &$moved): void {
                if (! $file->model instanceof HasMedia) {
                    return;
                }

                $file->move($file->model, 'files', $disk);

                $moved++;
            });

        $this->components->info("Moved {$moved} file(s) to the [{$disk}] disk.");

        return self::SUCCESS;
    }
}
