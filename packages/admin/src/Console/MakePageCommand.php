<?php

declare(strict_types=1);

namespace Shopper\Console;

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Features\SupportConsoleCommands\Commands\FileManipulationCommand;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\error;
use function Laravel\Prompts\text;

#[AsCommand(name: 'make:shopper-page')]
class MakePageCommand extends FileManipulationCommand
{
    protected $signature = 'make:shopper-page {name?} {--f|force}';

    protected $description = 'Create a new Shopper page class and view';

    public function handle(): void
    {
        $page = $this->getPageName();

        $classPath = $this->classPath($page);
        $viewPath = $this->viewPath($page);

        $view = $this->createView($page, $viewPath);
        $class = $this->createClass($page, $classPath);

        if ($view || $class) {
            $this->components->info('Shopper Page Created 🚀');
            $class && $this->line('<options=bold;fg=green>Class :</> '.mb_ltrim(str_replace(base_path(), '', $classPath), DIRECTORY_SEPARATOR));
            $view && $this->line('<options=bold;fg=green>View :</>  '.mb_ltrim(str_replace(base_path(), '', $viewPath), DIRECTORY_SEPARATOR));
        }
    }

    protected function createClass(string $page, string $classPath): false|string
    {
        if (File::exists($classPath) && ! $this->option('force')) {
            error('Class already exists');

            return false;
        }

        $this->ensureDirectoryExists($classPath);

        File::put($classPath, $this->classContents($page));

        return $classPath;
    }

    protected function createView(string $page, string $viewPath): false|string
    {
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
            ->map(fn (string $segment) => Str::kebab($segment))
            ->implode('.');
    }

    protected function getStub(string $type): string
    {
        $stubsDirectory = __DIR__.'/../../stubs';

        return match ($type) { // @phpstan-ignore-line
            'class' => $stubsDirectory.'/page-component.stub',
            'view' => $stubsDirectory.'/view.stub',
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

    protected function classContents(string $page): string
    {
        $stubPath = $this->getStub('class');

        return preg_replace(
            ['/\[namespace\]/', '/\[class\]/', '/\[view\]/'],
            [$this->classNamespace($page), $this->className($page), $this->getView($page)],
            file_get_contents($stubPath)
        );
    }

    private function classPath(string $page): string
    {
        $namespace = $this->classNamespace($page);
        $appNamespace = mb_rtrim(app()->getNamespace(), '\\');
        $relativePath = str_replace('\\', DIRECTORY_SEPARATOR, str_replace($appNamespace.'\\', '', $namespace));

        return app_path($relativePath.DIRECTORY_SEPARATOR.$this->className($page).'.php');
    }

    private function viewPath(string $page): string
    {
        $basePath = mb_rtrim((string) config('shopper.admin.pages.view_path'), '/');
        $segments = $this->pageSegments($page);
        $kebabSegments = array_map(fn (string $segment): string => Str::kebab($segment), $segments);

        return $basePath.'/'.implode('/', $kebabSegments).'.blade.php';
    }

    private function classNamespace(string $page): string
    {
        $segments = $this->pageSegments($page);
        array_pop($segments);

        $baseNamespace = (string) config('shopper.admin.pages.namespace');

        if (empty($segments)) {
            return $baseNamespace;
        }

        $subNamespace = implode('\\', array_map([Str::class, 'studly'], $segments));

        return $baseNamespace.'\\'.$subNamespace;
    }

    private function className(string $page): string
    {
        $segments = $this->pageSegments($page);

        return Str::studly((string) end($segments));
    }

    /** @return array<int, string> */
    private function pageSegments(string $page): array
    {
        return array_values(array_filter(explode('\\', str_replace('/', '\\', $page))));
    }
}
