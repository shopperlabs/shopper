<?php

namespace Shopper\Framework\Console;

use Illuminate\Console\Command;

class SymlinkCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'shopper:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a symbolic link from "vendor/shopper" to "public/shopper" and add Storage symbolic link';

    /**
     * Execute the console command.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle()
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
