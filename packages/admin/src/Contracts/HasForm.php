<?php

namespace Shopper\Contracts;

use Illuminate\Database\Eloquent\Model;

interface HasForm
{
    public function rules(): array;

    public function save(Model|string $model): void;
}
