<?php

declare(strict_types=1);

namespace Shopper\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'shopper:link')]
final class SymlinkCommand extends Command
{
    protected $signature = 'shopper:link';

    protected $description = 'Create a symbolic link from "vendor/shopper" to "public/shopper" and add Storage symbolic link';

    public function handle(): void
    {
        $prefix = shopper()->prefix();
        $link = public_path($prefix);
        $target = realpath(__DIR__ . '/../../public/');

        if (file_exists($link)) {
            $this->error('The "public/' . $prefix . '" directory already exists.');
        } else {
            $this->laravel->make('files')->link($target, $link);
            $this->info('The [public/' . $prefix . '] directory has been linked.');
        }

        $this->info('The link have been created.');
    }
}
