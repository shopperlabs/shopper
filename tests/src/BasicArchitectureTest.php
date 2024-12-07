<?php

declare(strict_types=1);

arch()
    ->expect('Shopper')
    ->toUseStrictTypes()
    ->not->toUse(['die', 'dd', 'dump', 'ray']);

arch()->preset()->laravel();
