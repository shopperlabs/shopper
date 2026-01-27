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

    protected $description = 'Create a symbolic link from "vendor/shopper" to public folder';

    public function handle(Filesystem $files): int
    {
        $prefix = shopper()->prefix();
        $link = public_path($prefix);
        $target = base_path('vendor/shopper/framework/public');

        if (! is_dir($target)) {
            $this->components->error("The target directory [$target] does not exist.");

            return self::FAILURE;
        }

        if (file_exists($link) && ! $this->isRemovableSymlink($link)) {
            $this->components->error("The [public/$prefix] link already exists.");

            return self::FAILURE;
        }

        if (is_link($link)) {
            $files->delete($link);
        }

        if ($this->option('relative')) {
            $files->relativeLink($target, $link);
        } else {
            $files->link($target, $link);
        }

        $this->components->info("The [public/$prefix] link has been connected to [$target].");

        return self::SUCCESS;
    }

    private function isRemovableSymlink(string $link): bool
    {
        return is_link($link) && $this->option('force');
    }
}
