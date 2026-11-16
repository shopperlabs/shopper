<?php

declare(strict_types=1);

namespace Shopper\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'shopper:link')]
final class SymlinkCommand extends Command
{
    protected $signature = 'shopper:link
                {--relative : Create the symbolic link using relative paths}
                {--force : Recreate existing symbolic links}';

    protected $description = 'Create the storage link and the "public/vendor/shopper" link to the panel assets';

    public function handle(Filesystem $files): int
    {
        $this->call('storage:link', [
            '--relative' => $this->option('relative'),
            '--force' => $this->option('force'),
        ]);

        $link = public_path('vendor/shopper');
        $target = base_path('vendor/shopper/framework/public');

        if (! is_dir($target)) {
            $this->components->error("The target directory [$target] does not exist.");

            return self::FAILURE;
        }

        if (file_exists($link) && ! $this->isRemovableSymlink($link)) {
            $this->components->error('The [public/vendor/shopper] link already exists.');

            return self::FAILURE;
        }

        if (is_link($link)) {
            $files->delete($link);
        }

        $this->removeLinkNamedAfterPrefix($files, $target);

        $files->ensureDirectoryExists(dirname($link));

        if ($this->option('relative')) {
            $files->relativeLink($target, $link);
        } else {
            $files->link($target, $link);
        }

        $this->components->info("The [public/vendor/shopper] link has been connected to [$target].");

        return self::SUCCESS;
    }

    /**
     * Earlier releases linked the assets at public/{prefix}, a real directory
     * the web server served instead of the panel's own /{prefix} route.
     */
    private function removeLinkNamedAfterPrefix(Filesystem $files, string $target): void
    {
        $link = public_path(shopper()->prefix());

        if (is_link($link) && realpath($link) === realpath($target)) {
            $files->delete($link);
        }
    }

    private function isRemovableSymlink(string $link): bool
    {
        return is_link($link) && $this->option('force');
    }
}
