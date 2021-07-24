<?php

namespace Shopper\Framework\Traits;

use ReflectionClass;
use Livewire\Livewire;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

trait CanRegisterLivewireComponentDirectories
{
    protected function registerLivewireComponentDirectory($directory, $namespace, $aliasPrefix = '')
    {
        collect((new Filesystem())->allFiles($directory))
            ->map(function (SplFileInfo $file) use ($namespace) {
                return (string) Str::of($namespace)
                    ->append('\\', $file->getRelativePathname())
                    ->replace(['/', '.php'], ['\\', '']);
            })
            ->filter(fn ($class) => is_subclass_of($class, Component::class) && ! (new ReflectionClass($class))->isAbstract())
            ->each(function ($class) use ($namespace, $aliasPrefix) {
                $alias = Str::of($class)
                    ->after($namespace . '\\')
                    ->replace(['/', '\\'], '.')
                    ->prepend($aliasPrefix)
                    ->explode('.')
                    ->map([Str::class, 'kebab'])
                    ->implode('.');

                Livewire::component($alias, $class);
            });
    }
}
