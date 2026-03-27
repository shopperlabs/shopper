<?php

declare(strict_types=1);

namespace Shopper\Upgrade\Mcp\Prompts;

use Illuminate\Support\Facades\View;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Prompt;
use ReflectionClass;

abstract class ShopperUpgradePrompt extends Prompt
{
    public function handle(): ResponseFactory
    {
        $bladePath = dirname((string) (new ReflectionClass(static::class))->getFileName())
            .'/'.$this->name().'.blade.php';

        return Response::make(
            Response::text(View::file($bladePath)->render())
        );
    }
}
