<?php

declare(strict_types=1);

namespace Shopper\Framework\Console;

use Illuminate\Console\Command;

final class PublishCommand extends Command
{
    protected $signature = 'shopper:publish {--force : Overwrite any existing files}';

    protected $description = 'Publish all of the Shopper resources';

    public function handle(): void
    {
        $this->call('vendor:publish', [
            '--tag' => 'shopper-config',
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'shopper-seeders',
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'shopper-lang',
            '--force' => $this->option('force'),
        ]);
    }
}
