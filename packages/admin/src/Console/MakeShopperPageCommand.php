<?php

declare(strict_types=1);

namespace Shopper\Console;

use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'shopper:page')]
final class MakeShopperPageCommand extends MakePageCommand
{
    protected $signature = 'shopper:page {name?} {--f|force}';
}
