<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;

expect()->extend(
    name: 'toBeSameModel',
    extend: fn (Model $model) => $this->is($model)->toBeTrue()
);
