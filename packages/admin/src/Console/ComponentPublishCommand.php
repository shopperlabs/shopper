<?php

declare(strict_types=1);

namespace Shopper\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Finder\Finder;

#[AsCommand(name: 'shopper:component:publish')]
final class ComponentPublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shopper:component:publish
                    {name? : The name of the components config file to publish}
                    {--all : Publish all components config files}
                    {--force : Overwrite any existing components configuration files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish livewire components files in your application';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $config = $this->getBaseConfigurationFiles();
        dd($config);

        return 1;
    }

    /**
     * Publish the given file to the given destination.
     */
    protected function publish(string $name, string $file, string $destination): void
    {
        if (file_exists($destination) && ! $this->option('force')) {
            $this->components->error("The '{$name}' configuration file already exists.");

            return;
        }

        copy($file, $destination);

        $this->components->info("Published '{$name}' configuration file.");
    }

    /**
     * Get an array containing the base configuration files.
     */
    protected function getBaseConfigurationFiles(): array
    {
        $config = [];

        foreach (Finder::create()->files()->name('*.php')->in(__DIR__ . '/../../config/components') as $file) {
            $name = basename($file->getRealPath(), '.php');

            $config[$name] = $file->getRealPath();
        }

        return collect($config)->sortKeys()->all();
    }
}
