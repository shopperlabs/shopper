<?php

declare(strict_types=1);

namespace Shopper\StarterKit\Concerns;

use Closure;

use function Laravel\Prompts\spin;

trait HasConsoleTask
{
    private function task(string $title, Closure $callback): void
    {
        spin(callback: $callback, message: $title);

        $width = 52;
        $dots = str_repeat('.', max(1, $width - mb_strlen($title)));

        $this->taskOutput()->writeln("  <fg=#94A3B8>{$title}</> <fg=#334155>{$dots}</> <fg=#22C55E>✓</>");
    }
}
