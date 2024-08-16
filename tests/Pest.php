<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Shopper\Tests\TestCase;

uses(TestCase::class)->in(__DIR__ . '/src/Admin');

expect()->extend(
    name: 'toBeSameModel',
    extend: fn (Model $model) => $this->is($model)->toBeTrue()
);
