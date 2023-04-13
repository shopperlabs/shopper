<?php

declare(strict_types=1);

namespace Shopper\Framework\Console;

use Illuminate\Console\Command;

final class SymlinkCommand extends Command
{
    protected $signature = 'shopper:link';

    protected $description = 'Create a symbolic link from "vendor/shopper" to "public/shopper" and add Storage symbolic link';

    public function handle(): void
    {
        $link = public_path('shopper');
        $target = realpath(__DIR__ . '/../../public/');

        if (file_exists($link)) {
            $this->error('The "public/shopper" directory already exists.');
        } else {
            $this->laravel->make('files')->link($target, $link);
            $this->info('The [public/shopper] directory has been linked.');
        }

        $this->info('The link have been created.');
    }
}
