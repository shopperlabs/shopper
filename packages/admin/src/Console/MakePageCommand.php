<?php

declare(strict_types=1);

namespace Shopper\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Features\SupportConsoleCommands\Commands\ComponentParser;
use Livewire\Features\SupportConsoleCommands\Commands\FileManipulationCommand;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\error;
use function Laravel\Prompts\text;

#[AsCommand(name: 'make:shopper-page')]
final class MakePageCommand extends FileManipulationCommand
{
    protected $signature = 'make:shopper-page {name?} {--f|force}';

    protected $description = 'Create a new Shopper page class and view';

    public function handle(): void
    {
        $page = $this->getPageName();

        $parser = new ComponentParser(
            classNamespace: config('shopper.admin.pages.namespace'),
            viewPath: config('shopper.admin.pages.view_path'),
            rawCommand: $page
        );

        $view = $this->createView($parser);
        $class = $this->createClass($parser);

        if ($view || $class) {
            $this->components->info('Shopper Page Created 🚀');
            $class && $this->line("<options=bold;fg=green>Class :</> {$parser->relativeClassPath()}");
            $view && $this->line("<options=bold;fg=green>View :</>  {$parser->relativeViewPath()}");
        }
    }

    protected function createClass(ComponentParser $parser): false | string
    {
        $classPath = $parser->classPath();

        if (File::exists($classPath) && ! $this->option('force')) {
            error('Class already exists');

            return false;
        }

        $this->ensureDirectoryExists($classPath);

        File::put($classPath, $this->classContents($parser));

        return $classPath;
    }

    protected function createView(ComponentParser $parser): false | string
    {
        $viewPath = $parser->viewPath();

        if (File::exists($viewPath) && ! $this->option('force')) {
            error('View already exists');

            return false;
        }

        $this->ensureDirectoryExists($viewPath);

        File::put($viewPath, $this->viewContents());

        return $viewPath;
    }

    protected function getPageName(): string
    {
        return (string) str(
            $this->argument('name') ??
            text(
                label: 'What is the page name?',
                placeholder: 'CardListing',
                required: true,
            ),
        )
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');
    }

    protected function getView(string $page): string
    {
        $segments = explode('/', str_replace('\\', '/', $page));
        $name = array_pop($segments);

        $path = [
            'shopper',
            ...$segments,
        ];

        $path[] = $name;

        return collect($path)
            ->map(fn ($segment) => Str::kebab($segment))
            ->implode('.');
    }

    protected function getStub(string $type): string
    {
        $stubsDirectory = __DIR__ . '/../../stubs';

        return match ($type) {
            'class' => $stubsDirectory . '/page-component.stub',
            'view' => $stubsDirectory . '/view.stub',
        };
    }

    protected function viewContents(): string
    {
        $stubPath = $this->getStub('view');

        return preg_replace(
            '/\[quote\]/',
            Inspiring::quotes()->random(),
            file_get_contents($stubPath)
        );
    }

    protected function classContents(ComponentParser $parser): string
    {
        $stubPath = $this->getStub('class');

        return preg_replace(
            ['/\[namespace\]/', '/\[class\]/', '/\[view\]/'],
            [$parser->classNamespace(), $parser->className(), $parser->viewName()],
            file_get_contents($stubPath)
        );
    }
}
