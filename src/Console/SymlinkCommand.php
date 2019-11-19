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
    protected $description = 'Create a symbolic link from "vendor/shopper" to "public/shopper" and add Storage simnolic link';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle()
    {
        if (file_exists(public_path('shopper'))) {
            return $this->error('The "public/shopper" directory already exists.');
        }

        $this->laravel->make('files')->link(realpath(__DIR__ . '/../../public/'), public_path('shopper'));
        $this->info('The [public/shopper] directory has been linked.');
    }
}
