<?php

declare(strict_types=1);

namespace Shopper\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'shopper:publish')]
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
            '--tag' => 'shopper-lang',
            '--force' => $this->option('force'),
        ]);
    }
}
