<?php

namespace Shopper\Framework\Traits\Mails;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use function is_array;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use RegexIterator;
use const T_CLASS;
use const T_NAMESPACE;
use const T_STRING;
use const T_WHITESPACE;

trait Mailables
{
    public static function getMailables()
    {
        return self::mailablesList();
    }

    public static function getMailable($key, $name)
    {
        return collect(self::getMailables())->where($key, $name);
    }

    public static function buildMailable($instance, $type = 'call')
    {
        if ($type === 'call') {
            if (null !== self::handleMailableViewDataArgs($instance)) {
                return Container::getInstance()->call([self::handleMailableViewDataArgs($instance), 'build']);
            }

            return Container::getInstance()->call([new $instance(), 'build']);
        }

        return Container::getInstance()->make($instance);
    }

    /**
     * Handle Mailable Constructor arguments and pass the fake ones.
     *
     * @throws ReflectionException
     */
    public static function handleMailableViewDataArgs($mailable)
    {
        if (method_exists($mailable, '__construct')) {
            $reflection = new ReflectionClass($mailable);

            $params = $reflection->getConstructor()->getParameters();

            DB::beginTransaction();

            $args = collect($params)->map(function ($param) {
                if ($param->getType() !== null) {
                    if (class_exists($param->getType()->getName())) {
                        $parameters = [
                            'is_instance' => true,
                            'instance' => $param->getType()->getName(),
                        ];
                    } elseif ($param->getType()->getName() === 'array') {
                        $parameters = [
                            'is_array' => true,
                            'arg' => $param->getName(),
                        ];
                    } else {
                        $parameters = $param->name;
                    }

                    return $parameters;
                }

                return $param->name;
            });

            $filteredparams = [];

            $reflector = new ReflectionClass($mailable);

            if (! $args->isEmpty()) {
                return $reflector->newInstanceArgs($filteredparams);
            }

            DB::rollBack();
        }
    }

    /**
     * Get mailables list.
     *
     * @return \Illuminate\Support\Collection
     *
     * @throws ReflectionException
     */
    protected static function mailablesList()
    {
        $fqcns = [];

        if (! file_exists(config('shopper.mails.mailables_dir'))) {
            return null;
        }
        $allFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(config('shopper.mails.mailables_dir')));
        $phpFiles = new RegexIterator($allFiles, '/\.php$/');
        $i = 0;

        foreach ($phpFiles as $phpFile) {
            $i++;
            $content = file_get_contents($phpFile->getRealPath());
            $tokens = token_get_all($content);
            $namespace = '';
            for ($index = 0; isset($tokens[$index]); $index++) {
                if (! isset($tokens[$index][0])) {
                    continue;
                }
                if ($tokens[$index][0] === T_NAMESPACE) {
                    $index += 2; // Skip namespace keyword and whitespace
                    while (isset($tokens[$index]) && is_array($tokens[$index])) {
                        $namespace .= $tokens[$index++][1];
                    }
                }
                if ($tokens[$index][0] === T_CLASS && $tokens[$index + 1][0] === T_WHITESPACE && $tokens[$index + 2][0] === T_STRING) {
                    $index += 2; // Skip class keyword and whitespace

                    [$name] = explode('.', $phpFile->getFilename());

                    $mailableClass = $namespace . '\\' . $tokens[$index][1];

                    if (! self::mailable_exists($mailableClass)) {
                        continue;
                    }

                    $reflector = new ReflectionClass($mailableClass);

                    if ($reflector->isAbstract()) {
                        continue;
                    }

                    $mailable_data = self::buildMailable($mailableClass);

                    if (null !== self::handleMailableViewDataArgs($mailableClass)) {
                        $mailable_view_data = self::getMailableViewData(self::handleMailableViewDataArgs($mailableClass), $mailable_data);
                    } else {
                        $mailable_view_data = self::getMailableViewData(new $mailableClass(), $mailable_data);
                    }

                    $fqcns[$i]['data'] = $mailable_data;
                    $fqcns[$i]['markdown'] = self::getMarkdownViewName($mailable_data);
                    $fqcns[$i]['name'] = $name;
                    $fqcns[$i]['namespace'] = $mailableClass;
                    $fqcns[$i]['filename'] = $phpFile->getFilename();
                    $fqcns[$i]['modified'] = $phpFile->getCTime();
                    $fqcns[$i]['viewed'] = $phpFile->getATime();
                    $fqcns[$i]['view_data'] = $mailable_view_data;
                    $fqcns[$i]['path_name'] = $phpFile->getPathname();
                    $fqcns[$i]['readable'] = $phpFile->isReadable();
                    $fqcns[$i]['writable'] = $phpFile->isWritable();
                    $fqcns[$i]['view_path'] = null;
                    $fqcns[$i]['text_view_path'] = null;

                    if (null !== $fqcns[$i]['markdown'] && View::exists($fqcns[$i]['markdown'])) {
                        $fqcns[$i]['view_path'] = View($fqcns[$i]['markdown'])->getPath();
                    }

                    if (null !== $fqcns[$i]['data']) {
                        if (null !== $fqcns[$i]['data']->view && View::exists($fqcns[$i]['data']->view)) {
                            $fqcns[$i]['view_path'] = View($fqcns[$i]['data']->view)->getPath();
                        }

                        if (null !== $fqcns[$i]['data']->textView && View::exists($fqcns[$i]['data']->textView)) {
                            $fqcns[$i]['text_view_path'] = View($fqcns[$i]['data']->textView)->getPath();
                            $fqcns[$i]['text_view'] = $fqcns[$i]['data']->textView;
                        }
                    }

                    // break if you have one class per file (psr-4 compliant)
                    // otherwise you'll need to handle class constants (Foo::class)
                    break;
                }
            }
        }

        return collect($fqcns)->map(fn ($mailable) => $mailable)->reject(fn ($object) => ! method_exists($object['namespace'], 'build'));
    }

    /**
     * @param $mailable
     * @return mixed|void
     *
     * @throws ReflectionException
     */
    protected static function getMarkdownViewName($mailable)
    {
        if ($mailable === null) {
            return;
        }

        $reflection = new ReflectionClass($mailable);

        $property = $reflection->getProperty('markdown');

        $property->setAccessible(true);

        return $property->getValue($mailable);
    }

    /**
     * Determine if the mailable class exist.
     *
     * @return bool
     */
    protected static function mailable_exists($mailable)
    {
        if (! class_exists($mailable)) {
            return false;
        }

        return true;
    }

    protected static function viewDataInspect($param)
    {
        if ($param instanceof \Illuminate\Database\Eloquent\Model) {
            return [
                'type' => 'model',
                'attributes' => collect($param->getAttributes()),
            ];
        }
        if ($param instanceof \Illuminate\Database\Eloquent\Collection) {
            return [
                'type' => 'eloquent-collection',
                'attributes' => $param->all(),
            ];
        }
        if ($param instanceof \Illuminate\Support\Collection) {
            return [
                'type' => 'collection',
                'attributes' => $param->all(),
            ];
        }
    }

    /**
     * @return array|\Illuminate\Support\Collection
     *
     * @throws ReflectionException
     */
    private static function getMailableViewData($mailable, $mailable_data)
    {
        $traitProperties = [];

        $data = new ReflectionClass($mailable);

        foreach ($data->getTraits() as $trait) {
            foreach ($trait->getProperties(ReflectionProperty::IS_PUBLIC) as $traitProperty) {
                $traitProperties[] = $traitProperty->name;
            }
        }

        $properties = $data->getProperties(ReflectionProperty::IS_PUBLIC);
        $allProps = [];

        foreach ($properties as $prop) {
            if ($prop->class === $data->getName() || $prop->class === get_parent_class($data->getName()) &&
                get_parent_class($data->getName()) !== 'Illuminate\Mail\Mailable' && ! $prop->isStatic()) {
                $allProps[] = $prop->name;
            }
        }

        $obj = self::buildMailable($mailable);

        if (null === $obj) {
            $obj = [];

            return collect($obj);
        }

        $classProps = array_diff($allProps, $traitProperties);

        $withFuncData = collect($obj->viewData)->keys();

        $mailableData = collect($classProps)->merge($withFuncData);

        $data = $mailableData->map(function ($parameter) use ($mailable_data) {
            return [
                'key' => $parameter,
                'value' => property_exists($mailable_data, $parameter) ? $mailable_data->{$parameter} : null,
                'data' => property_exists($mailable_data, $parameter) ? self::viewDataInspect($mailable_data->{$parameter}) : null,
            ];
        });

        return $data->all();
    }
}
