<?php

declare(strict_types=1);

namespace Shopper\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Finder\Finder;

use function Laravel\Prompts\select;

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
     *
     * @return int|void
     */
    public function handle()
    {
        $config = $this->getBaseConfigurationFiles();

        if (is_null($this->argument('name')) && $this->option('all')) {
            foreach ($config as $key => $file) {
                $this->publish($key, $file, $this->getShopperComponentsConfigFolder().'/'.$key.'.php');
            }

            return;
        }

        $name = (string) (is_null($this->argument('name')) ? select(
            label: 'Which components configuration file would you like to publish?',
            options: collect($config)->map(function (string $path) {
                return basename($path, '.php');
            }),
        ) : $this->argument('name'));

        if (! is_null($name) && ! isset($config[$name])) {
            $this->components->error('Unrecognized component configuration file.');

            return 1;
        }

        $this->publish($name, $config[$name], $this->getShopperComponentsConfigFolder().'/'.$name.'.php');
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

        if (! File::exists($this->getShopperComponentsConfigFolder())) {
            File::makeDirectory($this->getShopperComponentsConfigFolder());
        }

        copy($file, $destination);

        $this->components->info("Published '{$name}' components configuration file.");
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

    protected function getShopperComponentsConfigFolder(): string
    {
        return $this->laravel->configPath().'/shopper/components';
    }
}
