<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Traits;

use Closure;
use Illuminate\Routing\RouteDependencyResolverTrait;
use ReflectionFunction;

trait CallableTrait
{
    use RouteDependencyResolverTrait;

    /**
     * @throws \ReflectionException
     */
    public function call(Closure $callback = null, $caller = null): self
    {
        if ($callback instanceof Closure) {
            $parameters = $this->resolveMethodDependencies(
                [$caller],
                new ReflectionFunction($callback)
            );
            call_user_func_array($callback, $parameters);
        }

        return $caller;
    }
}
